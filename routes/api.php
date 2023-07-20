<?php

use App\Http\Controllers\Api\{
    GenreController,
    CategoryController,
    CastMemberController,
};
use Illuminate\Support\Facades\Route;

Route::apiResource('/categories', CategoryController::class);
Route::apiResource(
    name: '/genres',
    controller: GenreController::class
);
Route::apiResource('/cast_members', CastMemberController::class);

Route::get('/', function() {
    return response()->json(['message' => 'success']);
});
