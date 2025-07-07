<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class BranchController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(Branch::with('region')->get());
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'id' => 'required|string|size:10|unique:branches',
            'name' => 'required|string|max:100',
            'region_id' => 'nullable|string|exists:regions,id',
        ]);

        $branch = Branch::create($validated);
        return response()->json($branch, 201);
    }

    public function show(string $id): JsonResponse
    {
        $branch = Branch::with('region')->findOrFail($id);
        return response()->json($branch);
    }

    public function update(Request $request, string $id): JsonResponse
    {
        $branch = Branch::findOrFail($id);
        $validated = $request->validate([
            'name' => 'sometimes|string|max:100',
            'region_id' => 'nullable|string|exists:regions,id',
        ]);

        $branch->update($validated);
        return response()->json($branch);
    }

    public function destroy(string $id): JsonResponse
    {
        $branch = Branch::findOrFail($id);
        $branch->delete();
        return response()->json(null, 204);
    }
}