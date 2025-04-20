<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\PlantPotPrice;


class ProductController extends Controller
{
    public function index()
    {
        return Product::all()->map(function ($product) {
            return [
                'id' => $product->id,
                'name' => $product->name,
                'type' => $product->type,
                'price' => $product->final_price,
                'stock' => $product->stock,
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
    }

    public function store(Request $request)
    {
        $product = Product::create([
            'name' => $request->name,
            'type' => $request->type,
            'price' => $request->price,
            'pot_size' => $request->pot_size,
            'stock' => 0,
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

        return response()->json(['message' => 'Producto creado', 'product' => $product], 201);
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $product->update([
            'name' => $request->name,
            'type' => $request->type,
            'price' => $request->price,
            'pot_size' => $request->pot_size,
        ]);

        if ($request->has('pot_prices')) {
            $product->plantPotPrices()->delete();
            foreach ($request->pot_prices as $potPrice) {
                PlantPotPrice::create([
                    'product_id' => $product->id,
                    'pot_size' => $potPrice['pot_size'],
                    'price' => $potPrice['price'],
                ]);
            }
        }

        return response()->json(['message' => 'Producto actualizado', 'product' => $product], 200);
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();
        return response()->json(['message' => 'Producto eliminado'], 200);
    }
}
