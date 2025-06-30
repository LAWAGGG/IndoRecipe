<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\RecipeController;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix("/v1")->group(function () {
    Route::get("/categories", [CategoryController::class, "index"]);
    Route::get("/recipes", [RecipeController::class, "index"]);
    Route::get("/recipes/{slug}", [RecipeController::class, "show"]);
    Route::get("/best-recipes", [RecipeController::class, "bestRecipes"]);
    Route::post("/register", [AuthController::class, "register"]);
    Route::post("/login", [AuthController::class, "login"]);

    Route::middleware("user")->group(function () {
        Route::post("/logout", [AuthController::class, "logout"]);
        Route::get("/profile", [AuthController::class, "profile"]);

        Route::post("/recipes", [RecipeController::class, "store"]);
        Route::delete("/recipes/{slug}", [RecipeController::class, "destroy"]);

        Route::post("/recipes/{slug}/rating", [RatingController::class, "store"]);
        Route::post("/recipes/{slug}/comment", [CommentController::class, "store"]);

        Route::middleware("admin")->group(function(){
            Route::post("/categories", [CategoryController::class, "store"]);
            Route::delete("/categories/{slug}", [CategoryController::class, "destroy"]);
        });
    });
});
