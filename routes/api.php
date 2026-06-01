<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\FavoriteController;

Route::apiResource('categories', CategoryController::class);

Route::get('products/search', [ProductController::class, 'search']);
Route::apiResource('products', ProductController::class);

Route::get('/cart', [CartController::class, 'index']);
Route::post('/cart/add/{id}', [CartController::class, 'add']);
Route::delete('/cart/remove/{id}', [CartController::class, 'remove']);
Route::delete('/cart/clear', [CartController::class, 'clear']);

Route::get('/favorites', [FavoriteController::class, 'index']);
Route::post('/favorites/add/{id}', [FavoriteController::class, 'add']);
Route::delete('/favorites/remove/{id}', [FavoriteController::class, 'remove']);
