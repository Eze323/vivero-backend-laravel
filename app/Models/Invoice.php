<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
      use HasFactory;

    protected $fillable = [
        'supplier_id',
        'invoice_number',
        'issue_date',
        'total_amount',
        'status',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }
}
