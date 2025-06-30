<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function index()
    {
        $cat = Category::get();

        return response()->json([
            "categories" => $cat
        ]);
    }

    public function store(Request $request)
    {
        $val = Validator::make($request->all(), [
            "name" => "required",
            "slug" => "required|unique:categories,slug"
        ]);

        if ($val->fails()) {
            return response()->json([
                "message" => "invalid fields",
                "errors" => $val->errors()
            ], 422);
        }

        Category::create([
            "name" => $request->name,
            "slug" => $request->slug,
        ]);

        return response()->json([
            "message"=>"Category created successful"
        ]);
    }

    public function destroy($slug){
        $category = Category::where("slug", $slug)->first();

        if(!$category){
            return response()->json([
                "message"=>"category not found"
            ], 404);
        }

        $category->delete();

         return response()->json([
            "message"=>"Category deleted successful"
        ]);
    }
}
