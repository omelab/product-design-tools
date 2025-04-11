<?php

namespace App\Http\Controllers;

use App\Models\Design;
use Illuminate\Http\Request;

class DesignController extends Controller
{
    public function index()
    {
        return view('designer');
    }

    public function save(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'design_data' => 'required|json',
            'preview_image' => 'nullable|string',
        ]);

        $design = Design::create([
            'title' => $validated['title'],
            'design_data' => $validated['design_data'],
            'preview_image' => $validated['preview_image'] ?? null,
        ]);

        return response()->json([
            'id' => $design->id,
            'preview' => $design->preview_image ?: '/images/default-preview.png'
        ]);
    }

    public function load($id)
    {
        $design = Design::findOrFail($id);
        return response()->json($design);
    }

    public function list()
    {
        $designs = Design::latest()->take(10)->get();
        return response()->json($designs);
    }
}