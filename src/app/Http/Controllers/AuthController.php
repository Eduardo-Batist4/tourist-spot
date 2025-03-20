<?php

namespace App\Http\Controllers;

use App\Models\Explorer;
use App\Models\History;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
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

    public function register(Request $request) {
        $validateData = $request->validate([
            'name' => 'required|string|min:4|max:50',
            'age' => 'required|integer|min:18|max:100',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'email' => 'required|string|email:rfc,dns|unique:explorers,email',
            'password' => 'required|string'
        ]);

        $explorer = Explorer::create($validateData);

        History::create([
            'latitude' => $validateData['latitude'],
            'longitude' => $validateData['longitude'],
            'explorer_id' => $explorer->id,
        ]);

        return response()->json([
            'message' => 'Explorer created successfully!',
            'explorer' => $explorer
        ], 201);
    }
    
}
