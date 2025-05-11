<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // Define los atributos del producto que quieres exponer
        // Este Resource se usará principalmente dentro de SaleItemResource
        return [
            'id' => $this->id,
            'name' => $this->name,
            'category' => $this->category,
            // Incluye solo los atributos relevantes para un item de venta si es necesario
            // 'price' => $this->price, // Precio actual del producto (puede ser diferente al de la venta)
            // 'stock' => $this->stock,
            // 'pot_size' => $this->pot_size,
            // 'image_url' => $this->image_url,
        ];
    }
}
