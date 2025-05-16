<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\Purchase;
use App\Models\Product;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    public function index()
    {
        $invoices = Invoice::with(['supplier', 'purchases.product'])->get()->map(function ($invoice) {
            return [
                'id' => $invoice->id,
                'supplier' => $invoice->supplier->name ?? 'Unknown',
                'invoice_number' => $invoice->invoice_number,
                'issue_date' => $invoice->issue_date,
                'total_amount' => (float) $invoice->total_amount,
                'status' => $invoice->status,
                'purchases' => $invoice->purchases->map(function ($purchase) {
                    return [
                        'product' => $purchase->product->name ?? 'Unknown',
                        'quantity' => $purchase->quantity,
                        'purchase_price' => (float) $purchase->purchase_price,
                    ];
                })->toArray(),
            ];
        });

        Log::info('Invoices fetched:', $invoices->toArray());
        return response()->json($invoices);
    }

    public function store(Request $request)
    {
        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'invoice_number' => 'required|string|unique:invoices,invoice_number',
            'issue_date' => 'required|date',
            'total_amount' => 'required|numeric|min:0',
            'status' => 'required|in:pending,paid,cancelled',
            'purchases' => 'required|array|min:1',
            'purchases.*.product_id' => 'required|exists:products,id',
            'purchases.*.quantity' => 'required|integer|min:1',
            'purchases.*.purchase_price' => 'required|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            $invoice = Invoice::create([
                'supplier_id' => $request->supplier_id,
                'invoice_number' => $request->invoice_number,
                'issue_date' => $request->issue_date,
                'total_amount' => $request->total_amount,
                'status' => $request->status,
            ]);

            foreach ($request->purchases as $purchaseData) {
                $product = Product::findOrFail($purchaseData['product_id']);

                $purchase = Purchase::create([
                    'product_id' => $purchaseData['product_id'],
                    'supplier_id' => $request->supplier_id,
                    'invoice_id' => $invoice->id,
                    'quantity' => $purchaseData['quantity'],
                    'purchase_price' => $purchaseData['purchase_price'],
                    'purchase_date' => $request->issue_date,
                ]);

                // Actualizar stock
                $product->increment('stock', $purchaseData['quantity']);

                // Actualizar precio de costo en el producto
                $product->update(['price' => $purchaseData['purchase_price']]);
            }

            DB::commit();
            Log::info('Invoice created:', $invoice->toArray());
            return response()->json(['message' => 'Factura creada', 'invoice' => $invoice], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating invoice:', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Error al crear la factura'], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'invoice_number' => 'required|string|unique:invoices,invoice_number,' . $id,
            'issue_date' => 'required|date',
            'total_amount' => 'required|numeric|min:0',
            'status' => 'required|in:pending,paid,cancelled',
        ]);

        $invoice = Invoice::findOrFail($id);
        $invoice->update([
            'supplier_id' => $request->supplier_id,
            'invoice_number' => $request->invoice_number,
            'issue_date' => $request->issue_date,
            'total_amount' => $request->total_amount,
            'status' => $request->status,
        ]);

        Log::info('Invoice updated:', $invoice->toArray());
        return response()->json(['message' => 'Factura actualizada', 'invoice' => $invoice], 200);
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $invoice = Invoice::findOrFail($id);

            // Revertir stock de productos
            foreach ($invoice->purchases as $purchase) {
                $product = Product::findOrFail($purchase->product_id);
                $product->decrement('stock', $purchase->quantity);
            }

            $invoice->delete();
            DB::commit();
            Log::info('Invoice deleted:', ['id' => $id]);
            return response()->json(['message' => 'Factura eliminada'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting invoice:', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Error al eliminar la factura'], 500);
        }
    }
}