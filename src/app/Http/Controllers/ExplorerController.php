<?php

namespace App\Http\Controllers;

use App\Models\Explorer;
use App\Models\History;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ExplorerController extends Controller
{

    public function index()
    {
        $explorers = Explorer::all();

        return response($explorers, 200);
    }

    public function update(Request $request, string $id)
    {

        if (Auth::check() && Auth::id() != $request->route('id')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $explorer = Explorer::findOrFail($id);

        $validateData = $request->validate([
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180'
        ]);

        if (empty($validateData)) {
            return response()->json([
                'error' => 'At least one field (latitude or longitude) is required.',
            ], 400);
        }


        $explorer->update($validateData);

        History::create([
            'latitude' => $validateData['latitude'],
            'longitude' => $validateData['longitude'],
            'explorer_id' => $explorer->id,
        ]);

        return response()->json([
            'message' => 'Location successfully updated!',
            'explorer' => $explorer
        ], 200);
    }

    public function show(string $id)
    {
        $explorer = Explorer::findOrFail($id);

        $inventory = $explorer->load('items');

        return response()->json($inventory, 200);
    }
}
