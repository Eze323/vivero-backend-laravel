<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Sale;

class SaleController extends Controller
{
    public function index()
    {
        return Sale::with('product', 'customer')->get()->map(function ($sale) {
            return [
                'id' => $sale->id,
                'product' => $sale->product->name,
                'customer' => $sale->customer ? $sale->customer->name : 'N/A',
                'quantity' => $sale->quantity,
                'total_price' => $sale->total_price,
                'created_at' => $sale->created_at,
            ];
        });
    }

    public function store(Request $request)
    {
        $product = Product::findOrFail($request->product_id);
        $quantity = $request->quantity;

        $salePrice = $product->final_price;
        $totalPrice = $salePrice * $quantity;

        $sale = Sale::create([
            'user_id' => auth()->id(),
            'customer_id' => $request->customer_id,
            'product_id' => $product->id,
            'quantity' => $quantity,
            'total_price' => $totalPrice,
        ]);

        $product->decrement('stock', $quantity);

        return response()->json(['message' => 'Venta registrada', 'sale' => $sale], 201);
    }
}
