<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $fillable = [
        'name',
        'last_name',
        'company_name',
        'address',
        'phone',

    ];

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    public function supplierPrices()
    {
        return $this->hasMany(SupplierPrice::class);
    }
}
