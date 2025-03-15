<?php

namespace App\Http\Controllers;

use App\Models\Explorer;
use Illuminate\Http\Request;

class ExplorerController extends Controller
{

    public function index()
    {
        $explorers = Explorer::all();

        return response($explorers, 200);
    }

    public function store(Request $req)
    {
        $validateData = $req->validate([
            'name' => 'required|min:4|max:50',
            'age' => 'required|integer|min:18|max:100',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180'
        ]);

        $explorer = Explorer::create($validateData);

        return response()->json([
            'message' => 'Explorer created successfully!',
            'explorer' => $explorer
        ], 201);
    }
}
