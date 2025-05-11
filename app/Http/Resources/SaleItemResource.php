<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SaleItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // Define los atributos de un item de venta
        return [
            'id' => $this->id,
            'sale_id' => $this->sale_id,
            'product_id' => $this->product_id,
            'quantity' => $this->quantity,
            'unit_price' => $this->unit_price, // Usar el precio unitario almacenado en el item
            'subtotal' => $this->subtotal,     // Usar el subtotal almacenado en el item
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            // Incluir la relación 'product' cargada, transformada por ProductResource
            'product' => ProductResource::make($this->whenLoaded('product')),
        ];
    }
}
