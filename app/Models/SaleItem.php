<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SaleItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'sale_id',
        'product_id',
        'quantity',
        'unit_price', // <-- Corregido el typo
        'subtotal',
    ];

    // Relación con la venta padre
    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    // Relación con el producto
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Accesor para obtener el subtotal.
    // Como ya lo estamos almacenando en la base de datos, este accesor podría no ser necesario.
    // Si lo mantienes, asegúrate de que use el unit_price almacenado.
    // public function getSubtotalAttribute()
    // {
    //     // Usa $this->attributes['unit_price'] para asegurarte de usar el valor de la base de datos
    //     return $this->quantity * $this->attributes['unit_price'];
    //     // O simplemente devuelve el valor almacenado si ya lo calculas al guardar:
    //     // return $this->attributes['subtotal'];
    // }

    // Remueve el accesor getUnitPriceAttribute si estás almacenando el precio unitario
    // en la tabla sale_items, ya que el precio del producto puede cambiar.
    // public function getUnitPriceAttribute()
    // {
    //     return $this->product->final_price; // Esto obtiene el precio actual del producto, no el de la venta
    // }
}