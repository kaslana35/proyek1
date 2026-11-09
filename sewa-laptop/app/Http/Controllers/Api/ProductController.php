<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Laptop;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $laptops = Laptop::where('status', 'ready')->where('stok', '>', 0)->get();
        return response()->json(['status' => 'success', 'data' => $laptops], 200);
    }

    public function show($id)
    {
        $laptop = Laptop::find($id);
        if (!$laptop) return response()->json(['status' => 'error', 'message' => 'Laptop tidak ditemukan'], 404);
        return response()->json(['status' => 'success', 'data' => $laptop], 200);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'merk' => 'required|string|max:255',
            'tipe' => 'required|string|max:255',
            'harga_sewa_per_hari' => 'required|numeric',
            'stok' => 'required|integer|min:0',
            'status' => 'required|in:ready,maintenance,empty,not_ready'
        ]);

        $laptop = Laptop::create($data);
        return response()->json(['status' => 'success', 'data' => $laptop], 201);
    }

    public function update(Request $request, $id)
    {
        $laptop = Laptop::find($id);
        if (!$laptop) return response()->json(['message' => 'Laptop tidak ditemukan'], 404);

        $data = $request->validate([
            'merk' => 'sometimes|string|max:255',
            'tipe' => 'sometimes|string|max:255',
            'harga_sewa_per_hari' => 'sometimes|numeric',
            'stok' => 'sometimes|integer|min:0',
            'status' => 'sometimes|in:ready,maintenance,empty,not_ready'
        ]);

        $laptop->update($data);
        return response()->json(['status' => 'success', 'data' => $laptop], 200);
    }

    public function destroy($id)
    {
        $laptop = Laptop::find($id);
        if (!$laptop) return response()->json(['message' => 'Laptop tidak ditemukan'], 404);
        $laptop->delete();
        return response()->json(['status' => 'success', 'message' => 'Laptop dihapus'], 200);
    }
}
