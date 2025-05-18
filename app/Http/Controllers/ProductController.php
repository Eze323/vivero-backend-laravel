<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\PlantPotPrice;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['supplierPrices.supplier', 'purchases.supplier', 'plantPotPrices'])->get()->map(function ($product) {
            return $this->formatProduct($product);
        });
        
        return response()->json($products);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|in:planta,arbusto,plantin,otro,semilla,herramienta',
            'price' => 'required|numeric|min:0', // Precio de venta (para plantPotPrices)
            'cost_price' => 'required|numeric|min:0', // Precio de costo
            'stock' => 'required|integer|min:0',
            'pot_size' => 'nullable|in:pequeña,mediana,grande',
            'image_url' => 'nullable|url',
        ]);

        $product = Product::create([
            'name' => $request->name,
            'category' => $request->category,
            'price' => $request->cost_price, // Precio de costo
            'stock' => $request->stock,
            'pot_size' => $request->pot_size,
            'image_url' => $request->image_url,
        ]);

        if ($request->pot_size) {
            PlantPotPrice::updateOrCreate(
                ['product_id' => $product->id, 'pot_size' => $request->pot_size],
                ['price' => $request->price] // Precio de venta
            );
        }

        
        return response()->json(['message' => 'Producto creado', 'product' => $this->formatProduct($product)], 201);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|in:planta,arbusto,plantin,otro,semilla,herramienta',
            'price' => 'required|numeric|min:0', // Precio de venta (para plantPotPrices)
            'cost_price' => 'required|numeric|min:0', // Precio de costo
            'stock' => 'required|integer|min:0',
            'pot_size' => 'nullable|in:pequeña,mediana,grande',
            'image_url' => 'nullable|url',
        ]);

        $product = Product::findOrFail($id);
        $product->update([
            'name' => $request->name,
            'category' => $request->category,
            'price' => $request->cost_price, // Precio de costo
            'stock' => $request->stock,
            'pot_size' => $request->pot_size,
            'image_url' => $request->image_url,
        ]);

        if ($request->pot_size) {
            PlantPotPrice::updateOrCreate(
                ['product_id' => $product->id, 'pot_size' => $request->pot_size],
                ['price' => $request->price] // Precio de venta
            );
        } else {
            // Si no hay pot_size, elimina precios asociados
            PlantPotPrice::where('product_id', $product->id)->delete();
        }

     
        return response()->json(['message' => 'Producto actualizado', 'product' => $this->formatProduct($product)], 200);
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();
     
        return response()->json(['message' => 'Producto eliminado'], 200);
    }

    protected function formatProduct($product)
    {
        return [
            'id' => $product->id,
            'name' => $product->name,
            'category' => $product->category,
            'price' => (float) $product->final_price, // Precio de venta
            'cost_price' => (float) $product->price, // Precio de costo
            'stock' => $product->stock,
            'image_url' => $product->image_url,
            'pot_size' => $product->pot_size,
            'supplier_prices' => $product->supplierPrices ? $product->supplierPrices->map(function ($supplierPrice) {
                return [
                    'supplier_id' => $supplierPrice->supplier_id,
                    'supplier_name' => $supplierPrice->supplier->name ?? 'Unknown',
                    'purchase_price' => (float) $supplierPrice->purchase_price,
                    'valid_from' => $supplierPrice->valid_from,
                    'valid_to' => $supplierPrice->valid_to,
                ];
            })->toArray() : [],
            'purchases' => $product->purchases ? $product->purchases->map(function ($purchase) {
                return [
                    'supplier_name' => $purchase->supplier->name ?? 'Unknown',
                    'quantity' => $purchase->quantity,
                    'purchase_price' => (float) $purchase->purchase_price,
                    'purchase_date' => $purchase->purchase_date,
                ];
            })->toArray() : [],
        ];
    }
}