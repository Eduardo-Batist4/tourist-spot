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

    public function store(Request $req)
    {
        Explorer::findOrFail($req->explorer_id);

        $validateData = $req->validate([
            'name' => 'required|min:4|max:50',
            'value' => 'required|numeric|min:1',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'explorer_id' => 'required|numeric|min:1'
        ]);

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
            'explorer1_items.*' => 'required|integer|distinct|exists:items,id', // verifica se existe na tabela items

            'explorer2_id' => 'required|numeric|min:1',
            'explorer2_items.*' => 'required|integer|distinct|exists:items,id', // verifica se existe na tabela items
        ]);


        $explorer1 = Explorer::with('items')->findOrFail($request->explorer1_id); // search explorer with id 
        $explorer2 = Explorer::with('items')->findOrFail($request->explorer2_id); // search explorer with id 


        $valueExplorer1Item = $explorer1->items->whereIn('id', $request->explorer1_items); // verifica se os items passados na array, existe no inventario do explorer
        $valueExplorer2Item = $explorer2->items->whereIn('id', $request->explorer2_items);

        $totalItemsExplorer1 = 0;
        $totalItemsExplorer2 = 0;

        foreach ($valueExplorer1Item as $item) {
            $totalItemsExplorer1 += $item->value;
        }

        foreach ($valueExplorer2Item as $item) {
            $totalItemsExplorer2 += $item->value;
        }

        // verifica se os valores são iguais
        if ($totalItemsExplorer1 !=  $totalItemsExplorer2) {
            return response()->json('erro fdp no preço', 400);
        }

        foreach ($valueExplorer1Item as $item) {
            $oi = Item::findOrFail($item->id);
            $oi->update([
                'name' => $item->name,
                'value' => $item->value,
                'latitude' => $item->latitude,
                'longitude' => $item->longitude,
                'explorer_id' => $explorer2->id
            ]);
        }

        foreach ($valueExplorer2Item as $item) {
            $oi = Item::findOrFail($item->id);
            $oi->update([
                'name' => $item->name,
                'value' => $item->value,
                'latitude' => $item->latitude,
                'longitude' => $item->longitude,
                'explorer_id' => $explorer1->id
            ]);
        }

        return response()->json('deu', 200);
    }
}
