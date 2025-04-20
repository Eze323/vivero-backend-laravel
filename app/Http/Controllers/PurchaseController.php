<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Purchase;

class PurchaseController extends Controller
{
    public function index()
    {
        return Purchase::with('product', 'supplier')->get()->map(function ($purchase) {
            return [
                'id' => $purchase->id,
                'product' => $purchase->product->name,
                'supplier' => $purchase->supplier->name,
                'quantity' => $purchase->quantity,
                'purchase_price' => $purchase->purchase_price,
                'purchase_date' => $purchase->purchase_date,
            ];
        });
    }

    public function store(Request $request)
    {
        $product = Product::findOrFail($request->product_id);
        $purchase = Purchase::create([
            'product_id' => $request->product_id,
            'supplier_id' => $request->supplier_id,
            'quantity' => $request->quantity,
            'purchase_price' => $request->purchase_price,
            'purchase_date' => $request->purchase_date,
        ]);
        $product->increment('stock', $request->quantity);
        return response()->json(['message' => 'Compra registrada', 'purchase' => $purchase], 201);
    }
}
