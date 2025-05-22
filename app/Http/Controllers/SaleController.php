<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Sale;
use Illuminate\Support\Facades\DB;
use App\Models\SaleItem;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use App\Http\Resources\SaleResource;
use App\Http\Resources\SaleItemResource;
use App\Http\Resources\ProductResource;
use App\Http\Resources\CustomerResource;
use App\Services\SaleService;



class SaleController extends Controller
{
    /**
     * Display a listing of the sales with filters.
     * Loads items and related product/customer data.
     * Uses SaleResource for transformation.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request) // <-- Inyectar Request
    {
        // Start building the query
        $query = Sale::with('saleItems.product', 'customer');

        // Apply filters based on request parameters
        if ($request->has('search') && $request->search !== null) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('id', $searchTerm) // Filter by Sale ID
                  ->orWhere('seller', 'like', '%' . $searchTerm . '%') // Filter by Seller name
                  ->orWhere('customer', 'like', '%' . $searchTerm . '%') // Filter by Customer name (if stored directly)
                  ->orWhere('email', 'like', '%' . $searchTerm . '%') // Filter by Customer email (if stored directly)
                  // Optional: Filter by product name in items (requires a join or subquery)
                  ->orWhereHas('saleItems.product', function ($q2) use ($searchTerm) {
                      $q2->where('name', 'like', '%' . $searchTerm . '%');
                  });
            });
        }

        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        if ($request->has('date') && $request->date !== '') {
            // Filter by date (assuming 'YYYY-MM-DD' format from frontend)
            $query->whereDate('date', $request->date);
        }

        if ($request->has('seller') && $request->seller !== '') {
            $query->where('seller', $request->seller);
        }

        // Order the results (optional, but good practice)
        $query->orderBy('date', 'desc')->orderBy('time', 'desc');


        // Get the filtered and loaded sales
        $sales = $query->get();

        // Return a collection of SaleResource
        return SaleResource::collection($sales); // <-- Usar SaleResource::collection
    }

    /**
     * Store a newly created sale in storage.
     * Handles multiple items per sale.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    
public function store(Request $request)
{
    $request->validate([
        'customer' => 'required|string|max:255',
        'email' => 'nullable|email|max:255',
        'seller' => 'required|string|max:255',
        'date' => 'required|date',
        'time' => 'nullable|date_format:H:i',
        'status' => 'required|string|in:Pendiente,Completada,Cancelada',
        'items' => 'required|array|min:1',
        'items.*.product_id' => 'required|exists:products,id', // Cambiado de product name a product_id
        'items.*.quantity' => 'required|integer|min:1',
        'items.*.unitPrice' => 'required|numeric|min:0',
    ]);

    try {
        $sale = app(SaleService::class)->createSale($request->all(), auth()->id());
      
        return SaleResource::make($sale)->additional(['message' => 'Venta registrada'])->response()->setStatusCode(201);
    } catch (\Exception $e) {
      
        return response()->json(['message' => 'Error al registrar la venta', 'error' => $e->getMessage()], 500);
    }
}

    /**
     * Update the specified sale in storage.
     * Handles updating sale details and items.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Sale  $sale
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Sale $sale)
    {
         // --- 1. Validation (Similar to store, but maybe some fields are optional for update) ---
        $request->validate([
            'customer' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'seller' => 'required|string|max:255',
            'date' => 'required|date',
            'time' => 'nullable|date_format:H:i',
            'status' => 'required|string|in:Pendiente,Completada,Cancelada',
            'items' => 'required|array|min:0', // Allow 0 items for update? Or min:1?
            // IMPORTANT: Validate product_id if sending from frontend, or product name
            'items.*.product' => 'required|string|max:255', // Assuming product name is sent
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unitPrice' => 'required|numeric|min:0', // Validate unit price (frontend key)
             // If sending item IDs for existing items:
            'items.*.id' => 'nullable|exists:sale_items,id',
        ]);

        DB::beginTransaction(); // Start transaction

        try {
            // --- 2. Handle Stock Adjustments for Item Changes ---
            // Get current items before deleting them
            // Access the correct relationship name: saleItems
            $currentItems = $sale->saleItems->keyBy('id'); // <-- Usar 'saleItems'

            // Update Main Sale Record (do this first in case status changes)
            $sale->update($request->only(['customer', 'email', 'seller', 'date', 'time', 'status']));

            // Basic Stock Adjustment based on Status Change to Cancelled
            if ($request->status === 'Cancelada' && $sale->getOriginal('status') !== 'Cancelada') {
                 // If status changed to Cancelled, return stock for all current items
                 foreach ($currentItems as $item) {
                     $product = Product::find($item->product_id);
                     if ($product) {
                         $product->increment('stock', $item->quantity);
                     }
                 }
                 // After cancelling, we might not need to process new items,
                 // but the frontend might still send them. Let's proceed to sync items
                 // but be mindful of stock logic if status is Cancelled.
            }
            // Note: Handling stock changes for item quantity updates or product changes is more involved.
            // A robust solution would compare quantities between $currentItems and $request->items.

            // --- 3. Sync Sale Items ---
            // Delete existing items and re-create them based on the new list.
            // This is simpler than trying to update/delete individual items, but loses item ID continuity.
            // A more robust approach would involve comparing existing and new items by ID.

            // Simplified Sync (Delete and Re-create):
            // Access the correct relationship name: saleItems
            $sale->saleItems()->delete(); // <-- Usar 'saleItems'

            $totalPrice = 0;
            foreach ($request->items as $itemData) {
                 // Find the product again (or use product_id from frontend)
                 $product = Product::where('name', $itemData['product'])->first();
                 // OR: $product = Product::findOrFail($itemData['product_id']);

                 if (!$product) {
                     DB::rollBack();
                     return response()->json(['message' => 'Producto no encontrado al actualizar: ' . $itemData['product']], 404);
                 }

                 // Re-check stock if needed based on your stock adjustment logic
                 // If status is Cancelled, we might skip decrementing stock for new items added during update.
                 if ($sale->status !== 'Cancelada' && $product->stock < $itemData['quantity']) {
                      DB::rollBack();
                      return response()->json(['message' => 'Stock insuficiente para el producto: ' . $product->name . ' al actualizar'], 400);
                 }


                 $subtotal = $itemData['quantity'] * $itemData['unitPrice'];
                 $totalPrice += $subtotal;

                 // Create the new SaleItem
                 // Access the correct relationship name: saleItems
                 $sale->saleItems()->create([ // <-- Usar 'saleItems'
                     'product_id' => $product->id,
                     'quantity' => $itemData['quantity'],
                     'unit_price' => $itemData['unitPrice'], // Store the price at time of sale
                     'subtotal' => $subtotal,
                 ]);

                 // Re-adjust stock if needed based on your stock adjustment logic
                 // This is complex if items change quantity/product.
                 // For the simple delete/recreate, stock was handled by the initial status check.
                 // If not cancelled, stock should ideally be decremented for the *new* items here,
                 // but this overlaps with the initial stock check. This highlights the need for a more
                 // sophisticated stock adjustment logic in the update method.
            }

            // Update the total price on the main sale record
            $sale->update(['total_price' => $totalPrice]);


            DB::commit(); // Commit the transaction

            // --- 5. Return Response ---
            // Load the saleItems relationship with product details for the response
            // Return a single SaleResource for the updated sale
            return SaleResource::make($sale->load('saleItems.product')); // <-- Usar SaleResource::make

        } catch (\Exception $e) {
             DB::rollBack(); // Rollback transaction on any error
      
             return response()->json(['message' => 'Error al actualizar la venta', 'error' => $e->getMessage()], 500);
        }
    }

     /**
     * Remove the specified sale from storage.
     * Returns stock to products.
     *
     * @param  \App\Models\Sale  $sale
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Sale $sale)
    {
         DB::beginTransaction(); // Start transaction

        try {
            // Return stock for each item in the sale
            // Access the correct relationship name: saleItems
            foreach ($sale->saleItems as $item) { // <-- Usar 'saleItems'
                $product = Product::find($item->product_id);
                if ($product) {
                    $product->increment('stock', $item->quantity);
                }
            }

            // Delete the sale and its items (assuming cascade delete is NOT set up,
            // or explicitly deleting items first is safer)
            // Access the correct relationship name: saleItems
            $sale->saleItems()->delete(); // <-- Usar 'saleItems'
            $sale->delete(); // Delete the main sale record

            DB::commit(); // Commit the transaction

            return response()->json(['message' => 'Venta eliminada exitosamente']);

        } catch (\Exception $e) {
             DB::rollBack(); // Rollback transaction on any error
      
             return response()->json(['message' => 'Error al eliminar la venta', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified sale.
     *
     * @param  \App\Models\Sale  $sale
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Sale $sale)
    {
         // Load the sale with its saleItems and the product for each item, and the customer
        // Access the correct relationship name: saleItems
        // Return a single SaleResource for the sale
        return SaleResource::make($sale->load('saleItems.product', 'customer')); // <-- Usar SaleResource::make
    }
}
