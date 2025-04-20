<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    /** @use HasFactory<\Database\Factories\InvoiceFactory> */
    use HasFactory;
    protected $fillable = [
        'customer_id',
        'user_id',
        'amount',
        'status',
        'due_date',
        'paid_at',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
