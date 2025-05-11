<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Sale;

class EncargadoController extends Controller
{
    public function getSales()
    {
        $sales = Sale::with('product', 'user', 'customer')->get();
        return response()->json($sales);
    }

    public function storeSale(Request $request)
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

        return response()->json(['message' => 'Venta registrada', 'sale' => $sale]);
    }

    public function getProducts()
    {
        $products = Product::all()->map(function ($product) {
            return [
                'id' => $product->id,
                'name' => $product->name,
                'category' => $product->category,
                'price' => $product->final_price,
                'stock' => $product->stock,
                'image_url' => $product->image_url,
                'pot_size' => $product->pot_size,
                'supplier_prices' => $product->supplierPrices->map(function ($supplierPrice) {
                    return [
                        'supplier_id' => $supplierPrice->supplier_id,
                        'supplier_name' => $supplierPrice->supplier->name,
                        'purchase_price' => $supplierPrice->purchase_price,
                        'valid_from' => $supplierPrice->valid_from,
                        'valid_to' => $supplierPrice->valid_to,
                    ];
                }),
                'purchases' => $product->purchases->map(function ($purchase) {
                    return [
                        'supplier_name' => $purchase->supplier->name,
                        'quantity' => $purchase->quantity,
                        'purchase_price' => $purchase->purchase_price,
                        'purchase_date' => $purchase->purchase_date,
                    ];
                }),
            ];
        });
        return response()->json($products);
    }

    public function updateStock(Request $request, Product $product)
    {
        $product->update(['stock' => $request->stock]);
        return response()->json(['message' => 'Stock actualizado', 'product' => $product]);
    }
    
    public function storeProduct(Request $request)
{
    $product = Product::create([
        'name' => $request->name,
        'category' => $request->category,
        'price' => $request->price,
        'pot_size' => $request->pot_size,
        'stock' => 0,
        'image_url' => $request->image_url,
    ]);

    if ($request->has('pot_prices')) {
        foreach ($request->pot_prices as $potPrice) {
            PlantPotPrice::create([
                'product_id' => $product->id,
                'pot_size' => $potPrice['pot_size'],
                'price' => $potPrice['price'],
            ]);
        }
    }

    return response()->json(['message' => 'Producto creado', 'product' => $product]);
}
}
