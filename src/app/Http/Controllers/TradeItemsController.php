<?php

namespace App\Http\Controllers;

use App\Models\Explorer;
use App\Models\Item;
use App\Models\Trade;
use App\Models\TradeItem;
use Illuminate\Http\Request;

class TradeItemsController extends Controller
{
    public function index() {
        $tradeItems = TradeItem::all();

        return response()->json($tradeItems, 200);
    }

    public function store(Request $req) {

        $validateData = $req->validate([
            'trade_id' => 'required|numeric|min:1',
            'item_id' => 'required|numeric|min:1',
            'explorer_id' => 'required|numeric|min:1',
            'quantity' => 'required|numeric|min:1'
        ]);

        Trade::findOrFail($req->trade_id);
        $explorer = Explorer::findOrFail($req->explorer_id);
        Item::findOrFail($req->item_id);

        $all_items_id = $explorer->items->pluck('id');

        if(!$all_items_id->contains($req->item_id)) {
            return response()->json('This item does not belong to this explorer!', 400);
        }        
        
        $tradeItem = TradeItem::create($validateData);

        return response()->json($tradeItem, 200);
    }
}
