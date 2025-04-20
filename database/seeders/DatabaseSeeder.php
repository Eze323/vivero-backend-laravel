<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\PlantPotPrice;
use App\Models\Product;
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
        User::create([
            'name' => 'Admin',
            'email' => 'admin@vivero.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Encargado',
            'email' => 'encargado@vivero.com',
            'password' => Hash::make('password'),
            'role' => 'encargado',
        ]);

        User::create([
            'name' => 'Operario',
            'email' => 'operario@vivero.com',
            'password' => Hash::make('password'),
            'role' => 'operario',
        ]);

        // Proveedores
        $supplier1 = Supplier::create(['name' => 'Proveedor A', 'last_name' => 'Apellido A', 'company_name' => 'Compañía A', 'address' => 'Dirección A', 'phone' => '123456789']);
        $supplier2 = Supplier::create(['name' => 'Proveedor B', 'last_name' => 'Apellido B', 'company_name' => 'Compañía B', 'address' => 'Dirección B', 'phone' => '987654321']);

        // Productos
        $planta1 = Product::create([
            'name' => 'Rosa',
            'type' => 'planta',
            'price' => 500,
            'stock' => 10,
            'pot_size' => 'mediana',
        ]);

        $planta2 = Product::create([
            'name' => 'Cactus',
            'type' => 'planta',
            'price' => 800,
            'stock' => 20,
            'pot_size' => 'pequeña',
        ]);

        $planta3 = Product::create([
            'name' => 'Eugenia',
            'type' => 'arbusto',
            'price' => 300,
            'stock' => 20,
            'pot_size' => 'grande',
        ]);
        //plantines
        $planta4= Product::create([
            'name' => 'burrito',
            'type' => 'plantin',
            'price' => 350,
            'stock' => 50,
        ]);

        $otro = Product::create([
            'name' => 'Tierra',
            'type' => 'otro',
            'price' => 1200,
            'stock' => 50,
        ]);

        // Precios de macetas para plantas
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
        PlantPotPrice::create(['product_id' => $otro->id, 'pot_size' => 'pequeña', 'price' => 200]);
        PlantPotPrice::create(['product_id' => $otro->id, 'pot_size' => 'mediana', 'price' => 400]);
        PlantPotPrice::create(['product_id' => $otro->id, 'pot_size' => 'grande', 'price' => 1000]);

        // Precios de proveedores
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
            'product_id' => $otro->id,
            'supplier_id' => $supplier1->id,
            'purchase_price' => 1200,
            'valid_from' => Carbon::parse('2025-01-01'),
            'valid_to' => null,
        ]);

        // Clientes
        Customer::create([
            'name' => 'Cliente 1', 
            'last_name' => 'Apellido 1',
            'email'=>'cliente1@email.com',
            'phone'=>'123456789',
            'is_regular' => true,
            'last_name' => 'Apellido 1',

        ]);
        Customer::create([
            'name' => 'Cliente 2', 
            'last_name' => 'Apellido 2',
            'email'=>'cliente2@email.com',
            'phone'=>'987654321',
            'is_regular' => false]);
    }
}