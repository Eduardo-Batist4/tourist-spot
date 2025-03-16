<?php

namespace App\Http\Controllers;

use App\Models\Explorer;
use App\Models\Inventory;
use App\Models\Item;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function index() {
        $inventories = Inventory::all();

        return response()->json($inventories, 200);
    }
}
