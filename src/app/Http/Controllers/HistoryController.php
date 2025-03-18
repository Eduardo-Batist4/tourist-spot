<?php

namespace App\Http\Controllers;

use App\Models\Explorer;
use App\Models\History;
use Illuminate\Http\Request;

class HistoryController extends Controller
{
    public function index(string $id) {
        $explorer = Explorer::findOrFail($id);

        if($explorer->latitude == null || $explorer->longitude == null) {
            return response()->json(['message' => 'No location history']);
        }

        $history = $explorer->load('history');

        return response()->json($history, 200);
    }
}
