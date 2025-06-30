<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $val = Validator::make($request->all(), [
            "username" => "required|unique:users,username",
            "password" => "required|min:6|confirmed",
        ]);

        if ($val->fails()) {
            return response()->json([
                "message" => "invalid fields",
                "errors" => $val->errors()
            ], 422);
        }

        $token = uuid_create();

        User::create([
            "username" => $request->username,
            "password" => Hash::make($request->password),
            "role" => "User",
            "token" => $token
        ]);

        return response()->json([
            "message" => "Register success",
            "accessToken" => $token
        ]);
    }

    public function login(Request $request)
    {
        $val = Validator::make($request->all(), [
            "username" => "required",
            "password" => "required",
        ]);

        if ($val->fails()) {
            return response()->json([
                "message" => "invalid fields",
                "errors" => $val->errors()
            ], 422);
        }

        $token = uuid_create();

        $auth = Auth::attempt([
            "username" => $request->username,
            "password" => $request->password,
        ]);

        if (!$auth) {
            return response()->json([
                "message" => "Username or password incorrect"
            ], 401);
        }

        $token = uuid_create();

        auth()->user()->update([
            "token" => $token
        ]);

        return response()->json([
            "message" => "Register success",
            "role" => auth()->user()->role,
            "accessToken" => $token
        ]);
    }

    public function logout()
    {
        auth()->user()->update([
            "token" => null
        ]);

        return response()->json([
            "message" => "Logout success"
        ]);
    }

    public function profile()
    {
        $user = auth()->user();

        if (!$user) {
            return response()->json([
                "message" => "user not found"
            ], 404);
        }

        return response()->json([
            "id" => $user->id,
            "username" => $user->username,
            "role" => $user->role,
            "recipes" => collect($user->recipes)->map(function ($recipe) {
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
            }),
        ]);
    }
}
