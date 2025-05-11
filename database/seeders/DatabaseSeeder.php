<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\PlantPotPrice;
use App\Models\Product;
use App\Models\Sale; // Import the Sale model
use App\Models\SaleItem; // Import the SaleItem model
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
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        $encargadoUser = User::create([
            'name' => 'Romina',
            'email' => 'encargado@vivero.com',
            'password' => Hash::make('password'),
            'role' => 'encargado',
        ]);

        $operarioUser = User::create([
            'name' => 'Operario',
            'email' => 'operario@vivero.com',
            'password' => Hash::make('password'),
            'role' => 'operario',
        ]);

        // Proveedores (mantener como está)
        $supplier1 = Supplier::create(['name' => 'Proveedor A', 'last_name' => 'Apellido A', 'company_name' => 'Compañía A', 'address' => 'Dirección A', 'phone' => '123456789']);
        $supplier2 = Supplier::create(['name' => 'Proveedor B', 'last_name' => 'Apellido B', 'company_name' => 'Compañía B', 'address' => 'Dirección B', 'phone' => '987654321']);

        // Productos (mantener como está, agregando variables para usarlas en las ventas)
        $planta1 = Product::create([
            'name' => 'Rosa',
            'category' => 'planta',
            'price' => 500,
            'stock' => 10,
            'image_url' => 'https://images.pexels.com/photos/31962864/pexels-photo-31962864.jpeg',
            'pot_size' => 'mediana',
        ]);

        $planta2 = Product::create([
            'name' => 'Cactus',
            'category' => 'planta',
            'price' => 800,
            'stock' => 20,
            'image_url' => 'https://images.pexels.com/photos/1302305/pexels-photo-1302305.jpeg',
            'pot_size' => 'pequeña',
        ]);

        $planta3 = Product::create([
            'name' => 'Eugenia',
            'category' => 'arbusto',
            'price' => 300,
            'stock' => 20,
            'image_url' => 'https://eugeniasdelasabana.com/wp-content/uploads/2017/12/eu-5-600x600.jpg',
            'pot_size' => 'grande',
        ]);
        //plantines
        $planta4= Product::create([
            'name' => 'burrito',
            'category' => 'plantin',
            'price' => 350,
            'stock' => 50,
            'image_url' => 'https://sabordefazenda.com.br/wp-content/uploads/2021/02/WhatsApp-Image-2021-02-02-at-12.17.44.jpeg',
        ]);

        //plantas
        $planta5 = Product::create([
            'name' => 'Monstera Deliciosa',
            'category' => 'planta',
            'price' => 2500,
            'stock' => 15,
            'image_url' => 'https://images.pexels.com/photos/3097770/pexels-photo-3097770.jpeg',
        ]);
        $planta6 = Product::create([
            'name' => 'Ficus Lyrata',
            'category' => 'planta',
            'price' => 3200,
            'stock' => 8,
            'image_url' => 'https://images.pexels.com/photos/2123482/pexels-photo-2123482.jpeg',
        ]);
        $planta7 = Product::create([
            'name' => 'Semillas de Lavanda',
            'category' => 'semilla',
            'price' => 450,
            'stock' => 50,
            'image_url' => 'https://images.pexels.com/photos/4505161/pexels-photo-4505161.jpeg',
        ]);
        $planta8 = Product::create([
            'name' => 'Kit de Jardinería',
            'category' => 'herramienta',
            'price' => 1800,
            'stock' => 5,
             'image_url' => 'https://images.pexels.com/photos/1301856/pexels-photo-1301856.jpeg',
        ]);


        $tierra = Product::create([ // Renamed from $otro to $tierra for clarity
            'name' => 'Tierra Premium (saco)',
            'category' => 'otro',
            'price' => 1200,
            'stock' => 50,
            'image_url' => 'https://example.com/tierra.jpg',
        ]);

        // Precios de macetas para plantas (mantener como está)
        PlantPotPrice::create(['product_id' => $planta1->id, 'pot_size' => 'pequeña', 'price' => 350]);
        PlantPotPrice::create(['product_id' => $planta1->id, 'pot_size' => 'mediana', 'price' => 700]);
        PlantPotPrice::create(['product_id' => $planta1->id, 'pot_size' => 'grande', 'price' => 1800]);

        PlantPotPrice::create(['product_id' => $planta2->id, 'pot_size' => 'pequeña', 'price' => 300]);
        PlantPotPrice::create(['product_id' => $planta2->id, 'pot_size' => 'mediana', 'price' => 450]);
        PlantPotPrice::create(['product_id' => $planta2->id, 'pot_size' => 'grande', 'price' => 1200]);
        PlantPotPrice::create(['product_id' => $planta3->id, 'pot_size' => 'pequeña', 'price' => 400]);
        PlantPotPrice::create(['product_id' => $planta3->id, 'pot_size' => 'mediana', 'price' => 800]);
        PlantPotPrice::create(['product_id' => $planta3->id, 'pot_size' => 'grande', 'price' => 2000]);
        PlantPotPrice::create(['product_id' => $planta4->id, 'pot_size' => 'pequeña', 'price' => 300]);
        PlantPotPrice::create(['product_id' => $planta4->id, 'pot_size' => 'mediana', 'price' => 600]);
        PlantPotPrice::create(['product_id' => $planta4->id, 'pot_size' => 'grande', 'price' => 1500]);
        PlantPotPrice::create(['product_id' => $planta4->id, 'pot_size' => 'extra grande', 'price' => 2000]);
        PlantPotPrice::create(['product_id' => $planta4->id, 'pot_size' => 'extra extra grande', 'price' => 2500]);
        PlantPotPrice::create(['product_id' => $tierra->id, 'pot_size' => 'pequeña', 'price' => 200]);
        PlantPotPrice::create(['product_id' => $tierra->id, 'pot_size' => 'mediana', 'price' => 400]);
        PlantPotPrice::create(['product_id' => $tierra->id, 'pot_size' => 'grande', 'price' => 1000]);


        // Precios de proveedores (mantener como está)
        SupplierPrice::create([
            'product_id' => $planta1->id,
            'supplier_id' => $supplier1->id,
            'purchase_price' => 500,
            'valid_from' => Carbon::parse('2025-01-01'),
            'valid_to' => null,
        ]);

        SupplierPrice::create([
            'product_id' => $planta2->id,
            'supplier_id' => $supplier2->id,
            'purchase_price' => 800,
            'valid_from' => Carbon::parse('2025-01-01'),
            'valid_to' => null,
        ]);
        SupplierPrice::create([
            'product_id' => $planta3->id,
            'supplier_id' => $supplier1->id,
            'purchase_price' => 300,
            'valid_from' => Carbon::parse('2025-01-01'),
            'valid_to' => null,
        ]);
        SupplierPrice::create([
            'product_id' => $planta4->id,
            'supplier_id' => $supplier2->id,
            'purchase_price' => 350,
            'valid_from' => Carbon::parse('2025-01-01'),
            'valid_to' => null,
        ]);
        SupplierPrice::create([
            'product_id' => $tierra->id,
            'supplier_id' => $supplier1->id,
            'purchase_price' => 1200,
            'valid_from' => Carbon::parse('2025-01-01'),
            'valid_to' => null,
        ]);


        // Clientes (mantener como está, agregando variables para usarlas en las ventas)
        $customer1 = Customer::create([
            'name' => 'Carlos',
            'last_name' => 'Rodríguez',
            'email'=>'carlos@example.com',
            'phone'=>'123456789',
            'is_regular' => true,
        ]);
        $customer2 = Customer::create([
            'name' => 'Laura',
            'last_name' => 'Martínez',
            'email'=>'laura@example.com',
            'phone'=>'987654321',
            'is_regular' => false]);

        $customer3 = Customer::create([
            'name' => 'Miguel',
            'last_name' => 'Torres',
            'email'=>'miguel@example.com',
            'phone'=>'555555555',
             'is_regular' => true,
        ]);


        // --- Ventas de Ejemplo con Items ---

        // Venta 1
        $sale1 = Sale::create([
            'user_id' => $encargadoUser->id, // Asignar a un usuario vendedor
            'customer' => $customer1->name . ' ' . $customer1->last_name, // O usar customer_id si tienes esa columna
            'email' => $customer1->email,
            'seller' => $encargadoUser->name, // O usar seller_id si tienes esa columna
            'date' => '2025-04-15',
            'time' => '14:30',
            'status' => 'Completada',
            'total_price' => 0, // Se calculará sumando los subtotales de los items
        ]);

        // Items para Venta 1
        $item1_1 = SaleItem::create([
            'sale_id' => $sale1->id,
            'product_id' => $planta1->id, // Rosa
            'quantity' => 5,
            'unit_price' => 500, // Precio al momento de la venta
            'subtotal' => 5 * 500,
        ]);
         $item1_2 = SaleItem::create([
            'sale_id' => $sale1->id,
            'product_id' => $tierra->id, // Tierra Premium
            'quantity' => 2,
            'unit_price' => 1000, // Precio al momento de la venta
            'subtotal' => 2 * 1000,
        ]);
         $item1_3 = SaleItem::create([
            'sale_id' => $sale1->id,
            'product_id' => $planta8->id, // Kit de Jardinería
            'quantity' => 1,
            'unit_price' => 1500, // Precio al momento de la venta
            'subtotal' => 1 * 1500,
        ]);

        // Calcular y actualizar el total de la Venta 1
        $sale1->total_price = $item1_1->subtotal + $item1_2->subtotal + $item1_3->subtotal;
        $sale1->save();


        // Venta 2
        $sale2 = Sale::create([
            'user_id' => $encargadoUser->id,
            'customer' => $customer2->name . ' ' . $customer2->last_name,
            'email' => $customer2->email,
            'seller' => $encargadoUser->name,
            'date' => '2025-04-15',
            'time' => '15:45',
            'status' => 'Pendiente',
            'total_price' => 0,
        ]);

        // Items para Venta 2
         $item2_1 = SaleItem::create([
            'sale_id' => $sale2->id,
            'product_id' => $planta7->id, // Semillas de Lavanda
            'quantity' => 3,
            'unit_price' => 400,
            'subtotal' => 3 * 400,
        ]);
         $item2_2 = SaleItem::create([
            'sale_id' => $sale2->id,
            'product_id' => $planta2->id, // Cactus
            'quantity' => 2,
            'unit_price' => 800,
            'subtotal' => 2 * 800,
        ]);

        // Calcular y actualizar el total de la Venta 2
        $sale2->total_price = $item2_1->subtotal + $item2_2->subtotal;
        $sale2->save();


         // Venta 3
        $sale3 = Sale::create([
            'user_id' => $encargadoUser->id,
            'customer' => $customer3->name . ' ' . $customer3->last_name,
            'email' => $customer3->email,
            'seller' => $encargadoUser->name,
            'date' => '2025-04-15',
            'time' => '16:20',
            'status' => 'Completada',
            'total_price' => 0,
        ]);

        // Items para Venta 3
         $item3_1 = SaleItem::create([
            'sale_id' => $sale3->id,
            'product_id' => $planta5->id, // Monstera Deliciosa
            'quantity' => 1,
            'unit_price' => 2500,
            'subtotal' => 1 * 2500,
        ]);
         $item3_2 = SaleItem::create([
            'sale_id' => $sale3->id,
            'product_id' => $planta6->id, // Ficus Lyrata
            'quantity' => 1,
            'unit_price' => 3200,
            'subtotal' => 1 * 3200,
        ]);
         $item3_3 = SaleItem::create([
            'sale_id' => $sale3->id,
            'product_id' => $planta4->id, // burrito
            'quantity' => 4,
            'unit_price' => 350,
            'subtotal' => 4 * 350,
        ]);

        // Calcular y actualizar el total de la Venta 3
        $sale3->total_price = $item3_1->subtotal + $item3_2->subtotal + $item3_3->subtotal;
        $sale3->save();

    }
}
