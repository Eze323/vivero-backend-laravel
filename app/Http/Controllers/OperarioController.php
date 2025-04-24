<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EmbazadoRecord;

class OperarioController extends Controller
{
    public function getEmbazado()
    {
        $records = EmbazadoRecord::with('product', 'user')->get();
        return response()->json($records);
    }

    public function storeEmbazado(Request $request)
    {
        $record = EmbazadoRecord::create([
            'user_id' => auth()->id(),
            'product_id' => $request->product_id,
            'quantity' => $request->quantity,
        ]);
        return response()->json(['message' => 'Registro de embazado creado', 'record' => $record]);
    }
}
