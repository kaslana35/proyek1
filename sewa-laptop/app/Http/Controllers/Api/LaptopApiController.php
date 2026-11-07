<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Laptop;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LaptopApiController extends Controller
{
    // API Endpoint: Get All Laptops
    public function index()
    {
        $laptops = Laptop::where('status', 'ready')->get();
        return response()->json([
            'status' => 'success',
            'data' => $laptops
        ], 200);
    }

    // API Endpoint: Get Detail Laptop
    public function show($id)
    {
        $laptop = Laptop::find($id);
        if (!$laptop) {
            return response()->json(['status' => 'error', 'message' => 'Laptop tidak ditemukan'], 404);
        }
        return response()->json(['status' => 'success', 'data' => $laptop], 200);
    }

    // API Endpoint: Login & Get Token Sanctum
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['status' => 'error', 'message' => 'Kredensial salah'], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status' => 'success',
            'message' => 'Login Berhasil',
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user
        ], 200);
    }
}
