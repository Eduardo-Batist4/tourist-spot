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
            'name' => 'required|string|min:4|max:50',
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

    public function update(Request $req, string $id)
    {
        $explorer = Explorer::findOrFail($id);

        $validateData = $req->validate([
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180'
        ]);

        if (empty($validateData)) {
            return response()->json([
                'error' => 'At least one field (latitude or longitude) is required.',
            ], 400);
        }

        $explorer->update($validateData);

        return response()->json([
            'message' => 'Location successfully updated!',
            'explorer' => $explorer
        ], 200);
    }

    public function show(string $id)
    {

        $explorer = Explorer::findOrFail($id);

        $inventory = $explorer->load('inventories.item');

        return response()->json($inventory, 200);
    }
}
