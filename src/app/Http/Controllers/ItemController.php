<?php

namespace App\Http\Controllers;

use App\Models\Explorer;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ItemController extends Controller
{
    public function index()
    {
        $items = Item::all();

        return response()->json($items, 200);
    }

    public function store(Request $req)
    {
        
        Explorer::findOrFail($req->explorer_id);
        
        $validateData = $req->validate([
            'name' => 'required|min:4|max:50',
            'value' => 'required|numeric|min:1',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'explorer_id' => 'required|numeric|min:1|exists:explorers,id'
        ]);
        
        if (Auth::check() && Auth::id() != $validateData['explorer_id']) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        
        $item = Item::create($validateData);

        return response()->json([
            'message' => 'Item successfully added to inventory!',
            'item' => $item
        ], 201);
    }

    public function trade(Request $request)
    {
        $validateData = $request->validate([
            'explorer1_id' => 'required|numeric|min:1',
            'explorer1_items.*' => 'required|integer|distinct|exists:items,id', 

            'explorer2_id' => 'required|numeric|min:1',
            'explorer2_items.*' => 'required|integer|distinct|exists:items,id', 
        ]);


        $explorer1 = Explorer::with('items')->findOrFail($validateData['explorer1_id']); 
        $explorer2 = Explorer::with('items')->findOrFail($validateData['explorer2_id']); 


        $valueExplorer1Item = $explorer1->items->whereIn('id', $request->explorer1_items); 
        $valueExplorer2Item = $explorer2->items->whereIn('id', $request->explorer2_items);

        $totalItemsExplorer1 = 0;
        $totalItemsExplorer2 = 0;

        foreach ($valueExplorer1Item as $item) {
            $totalItemsExplorer1 += $item->value;
        }

        foreach ($valueExplorer2Item as $item) {
            $totalItemsExplorer2 += $item->value;
        }

        if ($totalItemsExplorer1 !=  $totalItemsExplorer2) {
            return response()->json('Unfair item price', 400);
        }

        foreach ($valueExplorer1Item as $item) {
            $item->update([
                'explorer_id' => $explorer2->id
            ]);
        }

        foreach ($valueExplorer2Item as $item) {
            $item->update([
                'explorer_id' => $explorer1->id
            ]);
        }

        return response()->json([
            'message' => 'Trade successfully concluded!'
        ], 200);
    }
}
