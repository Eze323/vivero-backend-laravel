<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Customer extends Model
{
    /** @use HasFactory<\Database\Factories\CustomerFactory> */
    use HasFactory;

    protected $fillable=[
        'name',
        'last_name',
        'email',
        'phone',
        'is_regular'
    ];
    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

    public function embazadoRecords()
    {
        return $this->hasMany(EmbazadoRecord::class);
    }

    public function invoinces()
    {
        return $this->hasMany(Invoice::class);
    }

    public function rewards()
    {
        return $this->hasMany(Reward::class);
    }

    public function getWeeklyPurchases($weekStartDate)
    {
        $weekEndDate = $weekStartDate->copy()->endOfWeek();
        return $this->sales()
            ->whereBetween('created_at', [$weekStartDate, $weekEndDate])
            ->count();
    }
}
