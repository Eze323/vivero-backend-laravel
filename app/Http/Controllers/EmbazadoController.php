<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EmbazadoRecord;

class EmbazadoController extends Controller
{
    public function index()
    {
        return EmbazadoRecord::with('product')->get()->map(function ($record) {
            return [
                'id' => $record->id,
                'product' => $record->product->name,
                'quantity' => $record->quantity,
                'created_at' => $record->created_at,
            ];
        });
    }

    public function store(Request $request)
    {
        $record = EmbazadoRecord::create([
            'user_id' => auth()->id(),
            'product_id' => $request->product_id,
            'quantity' => $request->quantity,
        ]);
        return response()->json(['message' => 'Registro de embazado creado', 'record' => $record], 201);
    }
}
