<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['name', 'type', 'price', 'stock', 'pot_size'];

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

    public function embazadoRecords()
    {
        return $this->hasMany(EmbazadoRecord::class);
    }

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    public function supplierPrices()
    {
        return $this->hasMany(SupplierPrice::class);
    }

    public function plantPotPrices()
    {
        return $this->hasMany(PlantPotPrice::class);
    }

    public function getFinalPriceAttribute()
    {
        $potPrice = $this->plantPotPrices()->where('pot_size', $this->pot_size)->first();
        return $potPrice ? $potPrice->price : $this->price;
    }
}
