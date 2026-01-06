<?php

namespace App\Http\Controllers;

use App\Models\Orderan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrderanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orderan = Orderan::orderBy('id', 'desc')->paginate(3);
        return view('admin.orderan.index', compact('orderan'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'harga' => 'required|string|max:50',
            'deadline' => 'required|date',
            'progres' => 'required|integer|min:0|max:100',
            'status' => 'required|in:In Progress,Active,Completed,Cancelled'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $orderan = Orderan::create($request->all());
        
        return response()->json([
            'success' => true,
            'message' => 'Orderan berhasil ditambahkan!',
            'data' => $orderan
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $orderan = Orderan::findOrFail($id);
        return response()->json([
            'success' => true,
            'data' => $orderan
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'harga' => 'required|string|max:50',
            'deadline' => 'required|date',
            'progres' => 'required|integer|min:0|max:100',
            'status' => 'required|in:In Progress,Active,Completed,Cancelled'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $orderan = Orderan::findOrFail($id);
        $orderan->update($request->all());
        
        return response()->json([
            'success' => true,
            'message' => 'Orderan berhasil diperbarui!',
            'data' => $orderan
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $orderan = Orderan::findOrFail($id);
        $orderan->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Orderan berhasil dihapus!'
        ]);
    }
}