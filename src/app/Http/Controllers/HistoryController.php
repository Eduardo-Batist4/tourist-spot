<?php

namespace App\Http\Controllers;

use App\Models\History;
use Illuminate\Http\Request;

class HistoryController extends Controller
{
    public function index(string $id) {
        $history = History::where('explorer_id', $id);
        
        return response()->json($history, 200);
    }
}
