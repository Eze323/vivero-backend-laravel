<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use App\Models\Customer;
use Carbon\Carbon;
use App\Models\Reward;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function getCustomers()
    {
        $weekStartDate = Carbon::today()->startOfWeek();

        $customers = Customer::with('sales')->get()->map(function ($customer) use ($weekStartDate) {
            $weeklyPurchases = $customer->getWeeklyPurchases($weekStartDate);
            $customer->is_regular = $weeklyPurchases >= 3;
            $customer->save();

            if ($customer->is_regular) {
                $existingReward = Reward::where('customer_id', $customer->id)
                    ->where('week_start_date', $weekStartDate)
                    ->first();

                if (!$existingReward) {
                    Reward::create([
                        'customer_id' => $customer->id,
                        'reward_type' => 'descuento',
                        'reward_value' => 10.00,
                        'week_start_date' => $weekStartDate,
                    ]);
                }
            }

            return [
                'id' => $customer->id,
                'name' => $customer->name,
                'weekly_purchases' => $weeklyPurchases,
                'is_regular' => $customer->is_regular,
                'reward' => $customer->rewards()
                    ->where('week_start_date', $weekStartDate)
                    ->first(['reward_type', 'reward_value']),
            ];
        });

        return response()->json($customers);
    }
}
