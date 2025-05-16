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
        return [
            'id' => $this->id,
            'name' => $this->name,
            'category' => $this->category,
            'price' => (float) $this->final_price,
            'cost_price' => (float) $this->price,
            'stock' => $this->stock,
            'image_url' => $this->image_url,
            'pot_size' => $this->pot_size,
            'supplier_prices' => $this->whenLoaded('supplierPrices', fn() => $this->supplierPrices->map(fn($sp) => [
                'supplier_id' => $sp->supplier_id,
                'supplier_name' => $sp->supplier->name ?? 'Unknown',
                'purchase_price' => (float) $sp->purchase_price,
                'valid_from' => $sp->valid_from,
                'valid_to' => $sp->valid_to,
            ])),
            'purchases' => $this->whenLoaded('purchases', fn() => $this->purchases->map(fn($p) => [
                'supplier_name' => $p->supplier->name ?? 'Unknown',
                'quantity' => $p->quantity,
                'purchase_price' => (float) $p->purchase_price,
                'purchase_date' => $p->purchase_date,
            ])),
        ];
    }
}