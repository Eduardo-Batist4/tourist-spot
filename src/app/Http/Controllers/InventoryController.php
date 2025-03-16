<?php

namespace App\Http\Controllers;

use App\Models\Explorer;
use App\Models\Inventory;
use App\Models\Item;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function index()
    {
        $invetory = Inventory::all();

        return response()->json($invetory, 200);
    }

    public function store(Request $req)
    {
        $validateData = $req->validate([
            'explorer_id' => 'required|integer|min:1',
            'item_id' => 'required|integer|min:1',
            'quantity' => 'required|integer|min:1|max:100'
        ]);


        Explorer::findOrFail($req->explorer_id);
        Item::findOrFail($req->item_id);

        $inventory = Inventory::create($validateData);

        return response()->json([
            'message' => 'Item successfully added to inventory!',
            'inventory' => $inventory
        ], 201);
    }

    public function show(string $id) {

        $inventory = Inventory::with(['explorer', 'item'])->findOrFail($id);

        return response()->json($inventory, 200);
    }
}
