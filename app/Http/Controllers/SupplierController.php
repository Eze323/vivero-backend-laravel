<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SupplierController extends Controller
{
    public function index()
    {
        $suppliers = Supplier::all()->map(function ($supplier) {
            return [
                'id' => $supplier->id,
                'name' => $supplier->name,
                'last_name' => $supplier->last_name,
                'email' => $supplier->email,
                'phone' => $supplier->phone,
                'address' => $supplier->address,
            ];
        });
        Log::info('Suppliers fetched:', $suppliers->toArray());
        return response()->json($suppliers);
    }

    public function show($id)
    {
        $supplier = Supplier::findOrFail($id);
        $formattedSupplier = [
            'id' => $supplier->id,
            'name' => $supplier->name,
            'last_name' => $supplier->last_name,
            'email' => $supplier->email,
            'phone' => $supplier->phone,
            'address' => $supplier->address,
        ];
        Log::info('Supplier fetched:', $formattedSupplier);
        return response()->json($formattedSupplier);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
        ]);

        try {
            $supplier = Supplier::create($request->only(['name','last_name', 'email', 'phone', 'address']));
            Log::info('Supplier created:', $supplier->toArray());
            return response()->json($supplier, 201);
        } catch (\Exception $e) {
            Log::error('Error creating supplier:', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Error al crear el proveedor'], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
        ]);

        try {
            $supplier = Supplier::findOrFail($id);
            $supplier->update($request->only(['name', 'last_name', 'email', 'phone', 'address']));
            Log::info('Supplier updated:', $supplier->toArray());
            return response()->json($supplier);
        } catch (\Exception $e) {
            Log::error('Error updating supplier:', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Error al actualizar el proveedor'], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $supplier = Supplier::findOrFail($id);
            $supplier->delete();
            Log::info('Supplier deleted:', ['id' => $id]);
            return response()->json(['message' => 'Proveedor eliminado']);
        } catch (\Exception $e) {
            Log::error('Error deleting supplier:', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Error al eliminar el proveedor'], 500);
        }
    }
}