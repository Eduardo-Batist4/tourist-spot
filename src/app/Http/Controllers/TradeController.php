<?php

namespace App\Http\Controllers;

use App\Models\Explorer;
use App\Models\Trade;
use Illuminate\Http\Request;

class TradeController extends Controller
{
    public function index()
    {
        $trades = Trade::all();

        return response()->json($trades, 200);
    }

    public function store(Request $req)
    {
        $validateData = $req->validate([
            'explorer_from_id' => 'required|numeric|min:1',
            'explorer_to_id' => 'required|numeric|min:1',
            'status' => 'required|in:pending, completed, canceled'
        ]);

        Explorer::findOrFail($req->explorer_from_id);
        Explorer::findOrFail($req->explorer_to_id);

        $trade = Trade::create($validateData);

        return response()->json([
            'message' => 'Trade successfully registered!',
            'trade' => $trade
        ], 201);
    }

    public function destroy(string $id)
    {
        $trade = Trade::findOrFail($id);

        $trade->delete();

        return response()->json("Trade successfully deleted!", 204);
    }
}
