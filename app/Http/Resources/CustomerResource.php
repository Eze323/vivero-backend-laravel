<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Log; // Import Log

class CustomerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // $this->resource contiene el modelo que se está transformando.
        // Verificar si es una instancia válida del modelo Customer
        if (!$this->resource instanceof \App\Models\Customer) {
             // Loggear si recibimos algo inesperado
             Log::error('CustomerResource received unexpected input type.', [
                 'input_type' => gettype($this->resource),
                 'input_value' => $this->resource,
             ]);

             // Devolver una estructura por defecto o null si la entrada no es válida
             // Devolver un array vacío o con valores por defecto previene el error
             return [
                 'id' => null,
                 'name' => 'Cliente Desconocido',
                 'last_name' => null,
                 'full_name' => 'Cliente Desconocido',
                 'email' => null,
                 'phone' => null,
                 'is_regular' => false,
                 'created_at' => null,
                 'updated_at' => null,
                 // Puedes agregar un campo de error si quieres indicarlo en la respuesta API
                 // 'resource_error' => 'Invalid customer data',
             ];
        }

        // Si es una instancia válida de Customer, proceder con la transformación normal
        return [
            'id' => $this->id, // Esta es la línea potencialmente problemática (línea 19)
            'name' => $this->name,
            'last_name' => $this->last_name,
            'full_name' => $this->name . ' ' . $this->last_name,
            'email' => $this->email,
            'phone' => $this->phone,
            'is_regular' => $this->is_regular,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            // No incluyas relaciones aquí si este Resource se usa anidado (ej. dentro de Sale)
        ];
    }
}
