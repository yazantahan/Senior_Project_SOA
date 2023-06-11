<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::all();

        return response()->json(["list" => $categories], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
            'name' => 'required',
        ]);

        if ($validate) {
            $category = new Category();
            $category->name = $request->name;
            $category->save();

            return response()->json(['message' => 'Category Successfully stored!'], 200);
        }

        return response()->json("The name is required", 300);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $category = Category::find($id);

        return response()->json(['Category' => $category], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $category = Category::find($id);

        return response()->json(['Category' => $category], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validate = $request->validate([
            'name' => 'required'
        ]);

        if ($validate) {
            $category = Category::find($id);
            $category->name = $request->name;
            $category->save();

            return response()->json(['message' => 'Category Successfully updated!'], 200);
        }

        return response()->json(['message' => 'The name is required'], 300);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $category = Category::destroy($id);
        return response()->json(['message' => 'Category Successfully Deleted!', 'Category'=>$category], 200);
    }
}
