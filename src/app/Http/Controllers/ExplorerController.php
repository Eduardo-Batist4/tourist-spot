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

    public function login(Request $request)
    {
        $validateData = $request->validate([
            'email' => 'required|string|email:rfc,dns|exists:explorers,email',
            'password' => 'required|string'
        ]);

        $explorer = Explorer::where('email', $validateData['email'])->first();

        if (! $explorer || ! Hash::check($validateData['password'], $explorer->password)) {
            return response()->json([
                'email' => 'The provided credentials are incorrect.',
            ], 400);
        }
        $token = $explorer->createToken('token')->plainTextToken;

        return response()->json([
            'message' => 'Login Successfully!',
            'token' => $token
        ], 200);
    }

    public function store(Request $req)
    {
        $validateData = $req->validate([
            'name' => 'required|string|min:4|max:50',
            'age' => 'required|integer|min:18|max:100',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'email' => 'required|string|email:rfc,dns|unique:explorers,email',
            'password' => 'required|string'
        ]);

        $explorer = Explorer::create($validateData);

        History::create([
            'latitude' => $explorer->latitude,
            'longitude' => $explorer->longitude,
            'explorer_id' => $explorer->id,
        ]);

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
