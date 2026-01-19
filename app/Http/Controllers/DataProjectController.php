<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DataProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $project = Project::orderBy('id', 'desc')->paginate(3);
        return view('general_manajer.data_project', compact('project'));
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
            'progres' => 'nullable|integer|min:0|max:100',
            'status' => 'required|in:In Progress,Active,Completed,Cancelled'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $project = Project::create($request->all());
        
        return response()->json([
            'success' => true,
            'message' => 'project berhasil ditambahkan!',
            'data' => $project
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $project = Project::findOrFail($id);
        return response()->json([
            'success' => true,
            'data' => $project
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

        $project = Project::findOrFail($id);
        $project->update($request->all());
        
        return response()->json([
            'success' => true,
            'message' => 'project berhasil diperbarui!',
            'data' => $project
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $project = Project::findOrFail($id);
        $project->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'project berhasil dihapus!'
        ]);
    }
}