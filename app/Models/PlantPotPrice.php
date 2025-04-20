<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlantPotPrice extends Model
{
    protected $fillable = ['product_id', 'pot_size', 'price'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
