<?php

namespace App\Http\Controllers;

use App\Models\Explorer;
use App\Models\Inventory;
use App\Models\Item;
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

    public function completeExchange(string $trade_id) {
        $trade = Trade::with('trade_items.item')->findOrFail($trade_id);

        $explorer_from_id = $trade->explorer_from_id; // explorer id
        $explorer_to_id = $trade->explorer_to_id; // explorer id

        $items_explorer_from = $trade->trade_items->where('explorer_id', $explorer_from_id); // items from explorer from
        $items_explorer_to = $trade->trade_items->where('explorer_id', $explorer_to_id); // item form explorer to


        $value_total_explorer_from = $items_explorer_from->sum(function ($tradeItem) {
            return $tradeItem->item->value * $tradeItem->quantity;
        }); // item value explorer from
        $value_total_explorer_to = $items_explorer_to->sum(function ($tradeItem) {
            return $tradeItem->item->value * $tradeItem->quantity;
        }); // item value explorer to

        if($value_total_explorer_from == $value_total_explorer_to) {
            $trade->update(['status' => 'completed']);
            
            foreach($items_explorer_from as $item_update) {
                $itemExplorer1 = Item::findOrFail($item_update->item->id);
                $itemExplorer1->update([
                    "name" => $item_update->item->name,
                    "value" => $item_update->item->value,
                    "latitude" => $item_update->item->latitude,
                    "longitude" => $item_update->item->longitude,
                    "explorer_id" => $explorer_to_id
                ]);
            }

            foreach($items_explorer_to as $item_update) {
                $itemExplorer2 = Item::findOrFail($item_update->item->id);
                $itemExplorer2->update([
                    "name" => $item_update->item->name,
                    "value" => $item_update->item->value,
                    "latitude" => $item_update->item->latitude,
                    "longitude" => $item_update->item->longitude,
                    "explorer_id" => $explorer_from_id
                ]);
            }

            return response()->json('Successful negociation!', 201);
        }

        $trade->update(['status' => 'canceled']);

        return response()->json([$value_total_explorer_from, $value_total_explorer_to,'Trade canceled! The values of the items are not the same'], 400);
    }
}
