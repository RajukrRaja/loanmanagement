<?php

namespace App\Http\Controllers;

use App\Models\Region;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class RegionController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(Region::with('branches')->get());
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'id' => 'required|string|size:10|unique:regions',
            'name' => 'required|string|max:100',
        ]);

        $region = Region::create($validated);
        return response()->json($region, 201);
    }

    public function show(string $id): JsonResponse
    {
        $region = Region::with('branches')->findOrFail($id);
        return response()->json($region);
    }

    public function update(Request $request, string $id): JsonResponse
    {
        $region = Region::findOrFail($id);
        $validated = $request->validate([
            'name' => 'sometimes|string|max:100',
        ]);

        $region->update($validated);
        return response()->json($region);
    }

    public function destroy(string $id): JsonResponse
    {
        $region = Region::findOrFail($id);
        $region->delete();
        return response()->json(null, 204);
    }
}