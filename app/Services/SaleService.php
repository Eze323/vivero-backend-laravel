<?php

namespace App\Services;

use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Product;
use App\Models\Customer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SaleService
{
    /**
     * Crear una nueva venta.
     *
     * @param array $data
     * @param int $userId
     * @return Sale
     * @throws \Exception
     */
    public function createSale(array $data, $userId)
    {
        DB::beginTransaction();
        try {
            // Buscar o crear cliente
            $customer = null;
            if (!empty($data['email'])) {
                $customer = Customer::where('email', $data['email'])->first();
                if ($customer) {
                    if ($customer->name !== $data['customer']) {
                        $customer->update(['name' => $data['customer']]);
                    }
                } else {
                    $customer = Customer::create([
                        'email' => $data['email'],
                        'name' => $data['customer'],
                        'last_name' => $data['last_name'] ?? null,
                        'phone' => $data['phone'] ?? null,
                        'is_regular' => false,
                    ]);
                }
            }

            // Calcular total y validar stock
            $totalPrice = 0;
            foreach ($data['items'] as $item) {
                $product = Product::findOrFail($item['product_id']);
                if ($data['status'] !== 'Cancelada' && $product->stock < $item['quantity']) {
                    throw new \Exception("Stock insuficiente para {$product->name}");
                }
                $totalPrice += $item['quantity'] * $item['unitPrice'];
            }

            // Crear venta
            $sale = Sale::create([
                'user_id' => $userId,
                'customer_id' => $customer ? $customer->id : null,
                'customer' => $data['customer'],
                'email' => $data['email'],
                'seller' => $data['seller'],
                'date' => $data['date'],
                'time' => $data['time'] ?? now()->format('H:i'),
                'status' => $data['status'],
                'total_price' => $totalPrice,
            ]);

            // Crear ítems de la venta
            foreach ($data['items'] as $item) {
                $product = Product::findOrFail($item['product_id']);
                SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unitPrice'],
                    'subtotal' => $item['quantity'] * $item['unitPrice'],
                ]);
                if ($data['status'] !== 'Cancelada') {
                    $product->decrement('stock', $item['quantity']);
                }
            }

            DB::commit();
            return $sale->load('saleItems.product', 'customer');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('SaleService::createSale failed:', ['error' => $e->getMessage()]);
            throw $e;
        }
    }
}