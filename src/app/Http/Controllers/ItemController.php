<?php

namespace App\Http\Controllers;

use App\Models\Explorer;
use App\Models\Inventory;
use App\Models\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function index()
    {
        $items = Item::all();

        return response()->json($items, 200);
    }

    public function store(Request $req, string $id)
    {
        Explorer::findOrFail($id);

        $validateData = $req->validate([
            'name' => 'required|min:4|max:50',
            'value' => 'required|numeric|min:1|max:2000000',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
        ]);

        $validateData['explorer_id'] = $id;

        $item = Item::create($validateData);

        return response()->json([
            'message' => 'Item successfully added to inventory!',
            'item' => $item
        ], 201);
    }
}
