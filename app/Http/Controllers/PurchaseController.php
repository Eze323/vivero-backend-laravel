<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Purchase;
use Illuminate\Support\Facades\Log;

class PurchaseController extends Controller
{
    public function index()
    {
        $purchases = Purchase::with(['product', 'supplier', 'invoice'])->get()->map(function ($purchase) {
            return [
                'id' => $purchase->id,
                'product' => $purchase->product->name ?? 'Unknown',
                'supplier' => $purchase->supplier->name ?? 'Unknown',
                'invoice_number' => $purchase->invoice->invoice_number ?? 'N/A',
                'quantity' => $purchase->quantity,
                'purchase_price' => (float) $purchase->purchase_price,
                'purchase_date' => $purchase->purchase_date,
            ];
        });

        Log::info('Purchases fetched:', $purchases->toArray());
        return response()->json($purchases);
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'invoice_id' => 'nullable|exists:invoices,id',
            'quantity' => 'required|integer|min:1',
            'purchase_price' => 'required|numeric|min:0',
            'purchase_date' => 'required|date',
        ]);

        $product = Product::findOrFail($request->product_id);

        $purchase = Purchase::create([
            'product_id' => $request->product_id,
            'supplier_id' => $request->supplier_id,
            'invoice_id' => $request->invoice_id,
            'quantity' => $request->quantity,
            'purchase_price' => $request->purchase_price,
            'purchase_date' => $request->purchase_date,
        ]);

        // Actualizar stock y precio de costo
        $product->increment('stock', $request->quantity);
        $product->update(['price' => $request->purchase_price]);

        Log::info('Purchase created:', $purchase->toArray());
        return response()->json(['message' => 'Compra registrada', 'purchase' => $purchase], 201);
    }
}