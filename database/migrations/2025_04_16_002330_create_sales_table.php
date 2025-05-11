<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            // Agrega estas columnas:
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Clave foránea al vendedor (si usas user_id)
            $table->string('customer'); // Nombre completo del cliente
            $table->string('email')->nullable(); // Email del cliente (puede ser opcional)
            $table->string('seller'); // Nombre del vendedor (si no usas user_id directamente para esto)
            $table->date('date'); // Fecha de la venta
            $table->time('time')->nullable(); // Hora de la venta (puede ser opcional)
            $table->string('status')->default('Pendiente'); // Estado de la venta (con un valor por defecto)
            $table->decimal('total_price', 10, 2)->default(0); // Precio total de la venta
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
