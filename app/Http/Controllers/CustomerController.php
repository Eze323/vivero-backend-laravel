<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use App\Models\Customer;
use Carbon\Carbon;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $weekStartDate = Carbon::parse('2025-04-07');
        return Customer::all()->map(function ($customer) use ($weekStartDate) {
            return [
                'id' => $customer->id,
                'name' => $customer->name,
                'weekly_purchases' => $customer->getWeeklyPurchases($weekStartDate),
                'is_regular' => $customer->is_regular,
                'reward' => $customer->rewards()
                    ->where('week_start_date', $weekStartDate)
                    ->first(['reward_type', 'reward_value']),
            ];
        });
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCustomerRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Customer $customer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Customer $customer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCustomerRequest $request, Customer $customer)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer)
    {
        //
    }
}
