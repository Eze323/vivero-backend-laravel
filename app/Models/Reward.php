<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reward extends Model
{
    protected $fillable = ['customer_id', 'reward_type', 'reward_value', 'week_start_date'];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
