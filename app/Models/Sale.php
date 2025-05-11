<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory; // Asegúrate de tener esto si usas factories

class Sale extends Model
{
    use HasFactory; // Asegúrate de tener esto si usas factories

    // Ajusta el fillable para que coincida con las columnas de tu tabla 'sales'
    // que NO están en 'sale_items'
    protected $fillable = [
        'user_id',
        // Si tienes customer_id, úsalo. Si no, asegúrate de que 'customer', 'email', etc. estén en la tabla sales
        'customer_id', // Si vinculas a la tabla customers
        'customer', // Si guardas el nombre del cliente directamente
        'email',
        'seller',
        'date',
        'time',
        'status',
        'total_price', // Este sí va en la tabla sales
    ];

    // Relación con el usuario (vendedor)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relación con el cliente (si tienes una tabla customers)
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    // Remueve la relación 'product' si ya no está directamente en la tabla sales

    // Relación con los items de la venta
    public function saleItems() // <-- Nombre de la relación
    {
        return $this->hasMany(SaleItem::class);
    }

    // Accesor para calcular el total si no lo almacenas,
    // pero ya lo estamos almacenando en el controlador, así que este accesor podría no ser necesario
    // Si lo mantienes, asegúrate de que sume los subtotales de la relación correcta
    // public function getTotalPriceAttribute()
    // {
    //     return $this->saleItems->sum('subtotal'); // Suma los subtotales de los items relacionados
    // }
}
