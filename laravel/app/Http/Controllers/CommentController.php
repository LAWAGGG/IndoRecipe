<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Recipe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{
    public function store(Request $request, $slug)
    {
        $recipe = Recipe::where("slug", $slug)->first();

        if (!$recipe) {
            return response()->json([
                "message" => "recipe not found"
            ], 404);
        }

        $val = Validator::make($request->all(), [
            "comment" => "required"
        ]);

        if ($val->fails()) {
            return response()->json([
                "message" => "invalid fields",
                "errors" => $val->errors()
            ], 422);
        }

        Comment::create([
            "comment" => $request->comment,
            "user_id" => auth()->user()->id,
            "recipe_id" => $recipe->id

        ]);

        return response()->json([
            "message" => "Comment success"
        ]);
    }
}
