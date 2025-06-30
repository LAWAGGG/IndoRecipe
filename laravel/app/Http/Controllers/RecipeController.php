<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RecipeController extends Controller
{
    public function index()
    {
        $recipe = collect(Recipe::get())->sortByDesc("created_at")->values();

        return response()->json([
            "recipes" => collect($recipe)->map(function ($recipe) {
                return [
                    "id" => $recipe->id,
                    "title" => $recipe->title,
                    "slug" => $recipe->slug,
                    "ingredients" => $recipe->ingredients,
                    "method" => $recipe->method,
                    "tips" => $recipe->tips,
                    "energy" => $recipe->energy . "kcal",
                    "carbohydrate" => $recipe->carbohydrate . "g",
                    "protein" => $recipe->protein . "g",
                    "thumbnail" => asset($recipe->thumbnail),
                    "created_at" => $recipe->created_at,
                    "author" => $recipe->user->username,
                    "ratings_avg" => round(collect($recipe->ratings)->average("rating"), 2),
                    "category" => $recipe->category,
                ];
            })
        ]);
    }

    public function show($slug)
    {
        $recipe = Recipe::where("slug", $slug)->first();

        if (!$recipe) {
            return response()->json([
                "message" => "recipe not found"
            ], 404);
        }

        return response()->json([
            "id" => $recipe->id,
            "title" => $recipe->title,
            "slug" => $recipe->slug,
            "ingredients" => $recipe->ingredients,
            "method" => $recipe->method,
            "tips" => $recipe->tips,
            "energy" => $recipe->energy . "kcal",
            "carbohydrate" => $recipe->carbohydrate . "g",
            "protein" => $recipe->protein . "g",
            "thumbnail" => asset($recipe->thumbnail),
            "created_at" => $recipe->created_at,
            "author" => $recipe->user->username,
            "ratings_avg" => round(collect($recipe->ratings)->average("rating"), 2),
            "category" => $recipe->category,
            "comments" => collect($recipe->comment)->map(function ($comment) {
                return [
                    "id" => $comment->id,
                    "comment" => $comment->comment,
                    "created_at" => $comment->created_at,
                    "comment_author" => $comment->user->username,
                ];
            })
        ]);
    }

    public function bestRecipes()
    {
        $recipe = Recipe::get();

        foreach ($recipe as $recipes) {
            $recipes["ratings_avg"] = round(collect($recipes->ratings)->average("rating"), 2);
        }

        $recipe = collect($recipe)->sortByDesc("retings_avg")->values()->take(3);

        return response()->json([
            "recipes" => collect($recipe)->map(function ($recipe) {
                return [
                    "id" => $recipe->id,
                    "title" => $recipe->title,
                    "slug" => $recipe->slug,
                    "ingredients" => $recipe->ingredients,
                    "method" => $recipe->method,
                    "tips" => $recipe->tips,
                    "energy" => $recipe->energy . "kcal",
                    "carbohydrate" => $recipe->carbohydrate . "g",
                    "protein" => $recipe->protein . "g",
                    "thumbnail" => asset($recipe->thumbnail),
                    "created_at" => $recipe->created_at,
                    "author" => $recipe->user->username,
                    "ratings_avg" => round(collect($recipe->ratings)->average("rating"), 2),
                    "category" => $recipe->category,
                ];
            })
        ]);
    }

    public function store(Request $request)
    {
        $val = Validator::make($request->all(), [
            "title" => "required",
            "category_id" => "required|exists:categories,id",
            "energy" => "required|numeric",
            "carbohydrate" => "required|numeric",
            "protein" => "required|numeric",
            "ingredients" => "required",
            "method" => "required",
            "tips" => "required",
            "thumbnail" => "nullable|image|mimes:png,jpg,jpeg",
        ]);

        if ($val->fails()) {
            return response()->json([
                "message" => "invalid fields",
                "errors" => $val->errors()
            ], 422);
        }

        $input = $request->all();
        $input["user_id"] = auth()->user()->id;
        if ($request->hasFile("thumbnail")) {
            $file = $request->file("thunbnail");
            $file->move(public_path() . "/recipes", "$request->slug.png");
            $file["thumbnial"] = "/recipes/$request->slug.png";
        }

        Recipe::create($input);

        return response()->json([
            'message' => 'Recipe created successfully'
        ]);
    }

    public function destroy($slug){
        $recipe = Recipe::where("slug", $slug)->first();

        if(!$recipe){
            return response()->json([
                "message"=>"recipe not found"
            ], 404);
        }

        if(auth()->user()->id != $recipe->user_id){
            return response()->json([
                "message"=>"Forbidden Access"
            ], 403);
        }

        $recipe->delete();

        return response()->json([
            "message"=>"Recipe deleted successful"
        ]);
    }
}
