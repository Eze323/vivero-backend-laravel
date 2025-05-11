<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log; // Importar Log

class SaleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // Obtener el valor de la relación customer después de cargarla
        $customerData = $this->whenLoaded('customer');

        // --- Agregar Logging aquí ---
        Log::info('Processing Sale ID: ' . $this->id . ' - Customer Data Type:', [
            'type' => gettype($customerData),
            'value' => $customerData, // Log the actual value
            'is_customer_instance' => $customerData instanceof \App\Models\Customer, // Check if it's a Customer model
            'is_null' => is_null($customerData), // Check if it's null
        ]);
        // --- Fin del Logging ---

        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            // Pasar el valor obtenido al CustomerResource
            'customer' => CustomerResource::make($customerData), // Pasar $customerData
            'customer_name_field' => $this->customer_name ?? null,
            'email' => $this->email,
            'seller' => $this->seller,
            'date' => $this->date,
            'time' => $this->time ? Carbon::parse($this->time)->format('H:i') : null,
            'status' => $this->status,
            'total_price' => $this->total_price,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'items' => SaleItemResource::collection($this->whenLoaded('saleItems')),
        ];
    }
}

