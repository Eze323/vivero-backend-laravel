<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\PlantPotPrice;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Supplier;
use App\Models\SupplierPrice;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Usuarios
        $adminUser = User::create([
            'name' => 'Jose',
            'email' => 'admin@vivero.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        $encargadoUser = User::create([
            'name' => 'Romina',
            'email' => 'encargado@vivero.com',
            'password' => Hash::make('password123'),
            'role' => 'encargado',
        ]);

        $operarioUser = User::create([
            'name' => 'Operario',
            'email' => 'operario@vivero.com',
            'password' => Hash::make('password123'),
            'role' => 'operario',
        ]);

        // Proveedores
        $supplier1 = Supplier::create([
            'name' => 'Proveedor A',
            'last_name' => 'Apellido A',
            'company_name' => 'Compañía A',
            'address' => 'Dirección A',
            'phone' => '123456789',
        ]);

        $supplier2 = Supplier::create([
            'name' => 'Proveedor B',
            'last_name' => 'Apellido B',
            'company_name' => 'Compañía B',
            'address' => 'Dirección B',
            'phone' => '987654321',
        ]);

        // Productos
        $planta1 = Product::create([
            'name' => 'Rosa',
            'category' => 'planta',
            'price' => 600,
            'stock' => 10,
            'image_url' => 'https://images.pexels.com/photos/31962864/pexels-photo-31962864.jpeg',
            'pot_size' => 'mediana',
            'description' => 'Rosa clásica de jardín, ideal para decoraciones.',
        ]);

        $planta2 = Product::create([
            'name' => 'Cactus',
            'category' => 'planta',
            'price' => 900,
            'stock' => 20,
            'image_url' => 'https://images.pexels.com/photos/1302305/pexels-photo-1302305.jpeg',
            'pot_size' => 'pequeña',
            'description' => 'Cactus resistente, requiere poco riego.',
        ]);

        $planta3 = Product::create([
            'name' => 'Eugenia',
            'category' => 'arbusto',
            'price' => 400,
            'stock' => 20,
            'image_url' => 'https://eugeniasdelasabana.com/wp-content/uploads/2017/12/eu-5-600x600.jpg',
            'pot_size' => 'grande',
            'description' => 'Arbusto ornamental para setos.',
        ]);

        $planta4 = Product::create([
            'name' => 'Burrito',
            'category' => 'plantin',
            'price' => 400,
            'stock' => 50,
            'image_url' => 'https://sabordefazenda.com.br/wp-content/uploads/2021/02/WhatsApp-Image-2021-02-02-at-12.17.44.jpeg',
            'description' => 'Plantín suculento, fácil de cultivar.',
        ]);

        $planta5 = Product::create([
            'name' => 'Monstera Deliciosa',
            'category' => 'planta',
            'price' => 2800,
            'stock' => 15,
            'image_url' => 'https://images.pexels.com/photos/3097770/pexels-photo-3097770.jpeg',
            'description' => 'Planta tropical con hojas grandes.',
        ]);

        $planta6 = Product::create([
            'name' => 'Ficus Lyrata',
            'category' => 'planta',
            'price' => 3500,
            'stock' => 8,
            'image_url' => 'https://images.pexels.com/photos/2123482/pexels-photo-2123482.jpeg',
            'description' => 'Ficus elegante para interiores.',
        ]);

        $planta7 = Product::create([
            'name' => 'Semillas de Lavanda',
            'category' => 'semilla',
            'price' => 500,
            'stock' => 50,
            'image_url' => 'https://images.pexels.com/photos/4505161/pexels-photo-4505161.jpeg',
            'description' => 'Semillas para cultivar lavanda aromática.',
        ]);

        $planta8 = Product::create([
            'name' => 'Kit de Jardinería',
            'category' => 'herramienta',
            'price' => 2000,
            'stock' => 5,
            'image_url' => 'https://images.pexels.com/photos/1301856/pexels-photo-1301856.jpeg',
            'description' => 'Kit completo para jardineros principiantes.',
        ]);

        $tierra = Product::create([
            'name' => 'Tierra Premium (saco)',
            'category' => 'sustrato',
            'price' => 1500,
            'stock' => 50,
            'image_url' => 'https://images.pexels.com/photos/5561378/pexels-photo-5561378.jpeg',
            'description' => 'Sustrato enriquecido para plantas.',
        ]);

        // Precios de macetas
        $potSizes = [
            ['product_id' => $planta1->id, 'pot_size' => 'pequeña', 'price' => 400],
            ['product_id' => $planta1->id, 'pot_size' => 'mediana', 'price' => 800],
            ['product_id' => $planta1->id, 'pot_size' => 'grande', 'price' => 2000],
            ['product_id' => $planta2->id, 'pot_size' => 'pequeña', 'price' => 350],
            ['product_id' => $planta2->id, 'pot_size' => 'mediana', 'price' => 500],
            ['product_id' => $planta2->id, 'pot_size' => 'grande', 'price' => 1300],
            ['product_id' => $planta3->id, 'pot_size' => 'pequeña', 'price' => 450],
            ['product_id' => $planta3->id, 'pot_size' => 'mediana', 'price' => 900],
            ['product_id' => $planta3->id, 'pot_size' => 'grande', 'price' => 2200],
            ['product_id' => $planta4->id, 'pot_size' => 'pequeña', 'price' => 350],
            ['product_id' => $planta4->id, 'pot_size' => 'mediana', 'price' => 700],
            ['product_id' => $planta4->id, 'pot_size' => 'grande', 'price' => 1700],
            ['product_id' => $planta4->id, 'pot_size' => 'extra grande', 'price' => 2200],
            ['product_id' => $planta4->id, 'pot_size' => 'extra extra grande', 'price' => 2800],
            ['product_id' => $planta5->id, 'pot_size' => 'mediana', 'price' => 1000],
            ['product_id' => $planta5->id, 'pot_size' => 'grande', 'price' => 2500],
            ['product_id' => $planta6->id, 'pot_size' => 'mediana', 'price' => 1200],
            ['product_id' => $planta6->id, 'pot_size' => 'grande', 'price' => 2700],
        ];

        foreach ($potSizes as $potSize) {
            PlantPotPrice::create($potSize);
        }

        // Precios de proveedores
        $supplierPrices = [
            ['product_id' => $planta1->id, 'supplier_id' => $supplier1->id, 'purchase_price' => 450, 'valid_from' => Carbon::parse('2025-01-01'), 'valid_to' => null],
            ['product_id' => $planta2->id, 'supplier_id' => $supplier2->id, 'purchase_price' => 650, 'valid_from' => Carbon::parse('2025-01-01'), 'valid_to' => null],
            ['product_id' => $planta3->id, 'supplier_id' => $supplier1->id, 'purchase_price' => 300, 'valid_from' => Carbon::parse('2025-01-01'), 'valid_to' => null],
            ['product_id' => $planta4->id, 'supplier_id' => $supplier2->id, 'purchase_price' => 300, 'valid_from' => Carbon::parse('2025-01-01'), 'valid_to' => null],
            ['product_id' => $planta5->id, 'supplier_id' => $supplier1->id, 'purchase_price' => 2000, 'valid_from' => Carbon::parse('2025-01-01'), 'valid_to' => null],
            ['product_id' => $planta6->id, 'supplier_id' => $supplier2->id, 'purchase_price' => 2500, 'valid_from' => Carbon::parse('2025-01-01'), 'valid_to' => null],
            ['product_id' => $planta7->id, 'supplier_id' => $supplier1->id, 'purchase_price' => 350, 'valid_from' => Carbon::parse('2025-01-01'), 'valid_to' => null],
            ['product_id' => $planta8->id, 'supplier_id' => $supplier2->id, 'purchase_price' => 1400, 'valid_from' => Carbon::parse('2025-01-01'), 'valid_to' => null],
            ['product_id' => $tierra->id, 'supplier_id' => $supplier1->id, 'purchase_price' => 1000, 'valid_from' => Carbon::parse('2025-01-01'), 'valid_to' => null],
        ];

        foreach ($supplierPrices as $price) {
            SupplierPrice::create($price);
        }

        // Clientes
        $customer1 = Customer::create([
            'name' => 'Carlos',
            'last_name' => 'Rodríguez',
            'email' => 'carlos@example.com',
            'phone' => '123456789',
            'address' => 'Calle Falsa 123, Buenos Aires',
            'is_regular' => true,
        ]);

        $customer2 = Customer::create([
            'name' => 'Laura',
            'last_name' => 'Martínez',
            'email' => 'laura@example.com',
            'phone' => '987654321',
            'address' => 'Avenida Siempre Viva 456, Mendoza',
            'is_regular' => false,
        ]);

        $customer3 = Customer::create([
            'name' => 'Miguel',
            'last_name' => 'Torres',
            'email' => 'miguel@example.com',
            'phone' => '555555555',
            'address' => 'Ruta 40, km 100, Córdoba',
            'is_regular' => true,
        ]);

        // Ventas
        $sales = [
            [
                'user_id' => $encargadoUser->id,
                'customer_id' => $customer1->id,
                'customer' => $customer1->name . ' ' . $customer1->last_name, // Added customer field
                'email' => $customer1->email,
                'seller' => $encargadoUser->name,
                'date' => '2025-05-24',
                'time' => '14:30:00',
                'status' => 'Completada',
                'total_price' => 0,
                'items' => [
                    ['product_id' => $planta1->id, 'quantity' => 5, 'unit_price' => 600],
                    ['product_id' => $tierra->id, 'quantity' => 2, 'unit_price' => 1500],
                    ['product_id' => $planta8->id, 'quantity' => 1, 'unit_price' => 2000],
                ],
            ],
            [
                'user_id' => $encargadoUser->id,
                'customer_id' => $customer2->id,
                'customer' => $customer2->name . ' ' . $customer2->last_name, // Added customer field
                'email' => $customer2->email,
                'seller' => $encargadoUser->name,
                'date' => '2025-05-24',
                'time' => '15:45:00',
                'status' => 'Pendiente',
                'total_price' => 0,
                'items' => [
                    ['product_id' => $planta7->id, 'quantity' => 3, 'unit_price' => 500],
                    ['product_id' => $planta2->id, 'quantity' => 2, 'unit_price' => 900],
                ],
            ],
            [
                'user_id' => $encargadoUser->id,
                'customer_id' => $customer3->id,
                'customer' => $customer3->name . ' ' . $customer3->last_name, // Added customer field
                'email' => $customer3->email,
                'seller' => $encargadoUser->name,
                'date' => '2025-05-24',
                'time' => '16:20:00',
                'status' => 'Completada',
                'total_price' => 0,
                'items' => [
                    ['product_id' => $planta5->id, 'quantity' => 1, 'unit_price' => 2800],
                    ['product_id' => $planta6->id, 'quantity' => 1, 'unit_price' => 3500],
                    ['product_id' => $planta4->id, 'quantity' => 4, 'unit_price' => 400],
                ],
            ],
        ];

        foreach ($sales as $saleData) {
            $sale = Sale::create([
                'user_id' => $saleData['user_id'],
                'customer_id' => $saleData['customer_id'],
                'customer' => $saleData['customer'], // Include customer field
                'email' => $saleData['email'],
                'seller' => $saleData['seller'],
                'date' => $saleData['date'],
                'time' => $saleData['time'],
                'status' => $saleData['status'],
                'total_price' => 0,
            ]);

            $totalPrice = 0;
            foreach ($saleData['items'] as $itemData) {
                $product = Product::find($itemData['product_id']);
                if ($product->stock < $itemData['quantity']) {
                    throw new \Exception("Stock insuficiente para el producto {$product->name}");
                }

                $subtotal = $itemData['quantity'] * $itemData['unit_price'];
                SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $itemData['product_id'],
                    'quantity' => $itemData['quantity'],
                    'unit_price' => $itemData['unit_price'],
                    'subtotal' => $subtotal,
                ]);

                // Update stock
                $product->stock -= $itemData['quantity'];
                $product->save();

                $totalPrice += $subtotal;
            }

            $sale->total_price = $totalPrice;
            $sale->save();
        }
    }
}