<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Sale;
use App\Services\SaleService;
use App\Http\Resources\SaleResource;
use App\Models\SaleItem;
use App\Http\Resources\ProductResource;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;


class EncargadoController extends Controller
{
    protected $saleService;

    public function __construct(SaleService $saleService)
    {
        $this->saleService = $saleService;
    }

    /**
     * Obtener todas las ventas con filtros y paginación.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSales(Request $request)
    {
        try {
            $query = Sale::with(['saleItems.product', 'user', 'customer']);

            // Aplicar filtros
            if ($request->has('search') && $request->search) {
                $query->where(function ($q) use ($request) {
                    $q->where('id', $request->search)
                      ->orWhere('customer', 'like', '%' . $request->search . '%')
                      ->orWhere('email', 'like', '%' . $request->search . '%')
                      ->orWhereHas('customer', fn($q2) => $q2->where('name', 'like', '%' . $request->search . '%')
                          ->orWhere('email', 'like', '%' . $request->search . '%'))
                      ->orWhereHas('saleItems.product', fn($q2) => $q2->where('name', 'like', '%' . $request->search . '%'));
                });
            }

            if ($request->has('status') && $request->status) {
                $query->where('status', $request->status);
            }

            if ($request->has('date') && $request->date) {
                $query->whereDate('date', $request->date);
            }

            if ($request->has('seller') && $request->seller) {
                $query->where('seller', $request->seller);
            }

            // Ordenar y paginar
            $sales = $query->orderBy('date', 'desc')
                          ->orderBy('time', 'desc')
                          ->paginate(20);

       
            return SaleResource::collection($sales);
        } catch (\Exception $e) {
       
            return response()->json(['message' => 'Error al obtener las ventas'], 500);
        }
    }

    /**
     * Crear una nueva venta.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeSale(Request $request)
    {
        $request->validate([
            'customer' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'seller' => 'required|string|max:255',
            'date' => 'required|date',
            'time' => 'nullable|date_format:H:i',
            'status' => 'required|in:Pendiente,Completada,Cancelada',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unitPrice' => 'required|numeric|min:0',
        ]);

        try {
            $sale = $this->saleService->createSale($request->all(), auth()->id());
       
            return SaleResource::make($sale)->additional(['message' => 'Venta registrada'])->response()->setStatusCode(201);
        } catch (\Exception $e) {
       
            return response()->json(['message' => 'Error al registrar la venta: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Actualizar el stock de un producto.
     *
     * @param Request $request
     * @param Product $product
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateStock(Request $request, Product $product)
    {
        $request->validate([
            'stock' => 'required|integer|min:0',
            'reason' => 'nullable|string|max:255', // Opcional para auditoría
        ]);

        try {
            // Verificar ventas pendientes que requieran stock
            $pendingSales = SaleItem::where('product_id', $product->id)
                ->whereHas('sale', fn($q) => $q->where('status', 'Pendiente'))
                ->sum('quantity');
            if ($request->stock < $pendingSales) {
                return response()->json(['message' => 'No se puede reducir el stock, hay ventas pendientes'], 422);
            }

            $product->update(['stock' => $request->stock]);
            // Log::info('Stock updated:', [
            //     'product_id' => $product->id,
            //     'stock' => $product->stock,
            //     'user_id' => auth()->id(),
            //     'reason' => $request->reason,
            // ]);
            return ProductResource::make($product->load('supplierPrices', 'purchases'))
                ->additional(['message' => 'Stock actualizado']);
        } catch (\Exception $e) {
            // Log::error('Error updating stock:', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Error al actualizar el stock: ' . $e->getMessage()], 500);
        }
    }
}