<?php

namespace App\Http\Controllers;

use App\Models\Rating;
use App\Models\Recipe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RatingController extends Controller
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
            "rating" => "required|numeric|between:1,5"
        ]);

        if ($val->fails()) {
            return response()->json([
                "message" => "invalid fields",
                "errors" => $val->errors()
            ], 422);
        }

        if (auth()->user()->id == $recipe->user_id) {
            return response()->json([
                "message" => "You cannot rate your own recipe"
            ], 403);
        }

        $rating = Rating::where("recipe_id", $recipe->id)->where("user_id", auth()->user()->id)->first();

        if ($rating) {
            return response()->json([
                "message" => "You have rated"
            ], 403);
        }

        Rating::create([
            "rating" => $request->rating,
            "user_id" => auth()->user()->id,
            "recipe_id" => $recipe->id
        ]);

        return response()->json([
            "message" => "Rating success"
        ]);
    }
}
