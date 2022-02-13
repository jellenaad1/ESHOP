<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CartController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\FrontendController;
use App\Http\Controllers\API\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);


Route::get('getCategory', [FrontendController::class, 'category']);

Route::get('fetchproducts/{slug}', [FrontendController::class, 'product']);
Route::get('viewproductdetail/{category_slug}/{product_slug}', [FrontendController::class, 'viewproduct']);

Route::post('add-to-cart',  [CartController::class, 'addToCart']);
Route::get('cart',  [CartController::class, 'viewcart']);
Route::get('cart',  [CartController::class, 'viewcart']);
Route::put('cart-update-qty/{cart_id}/{scope}',  [CartController::class, 'updateQuantity']);
Route::delete('delete-cartitem/{cart_id}',  [CartController::class, 'deleteCartItem']);














Route::middleware(['auth:sanctum', 'isAPIAdmin'])->group(function () {





    Route::get('/checkingAuthenticated', function () {
        return response()->json(['message' => 'You are in', 'status' => 200], 200);
    });

    //za kategoriju
    Route::get('view-category', [CategoryController::class, 'index']);
    Route::post('store-category', [CategoryController::class, 'store']);
    Route::get('edit-category/{id}', [CategoryController::class, 'edit']);
    Route::put('update-category/{id}', [CategoryController::class, 'update']);
    Route::delete('delete-category/{id}', [CategoryController::class, 'destroy']);

    Route::get('all-category', [CategoryController::class, 'allcategory']); //za kombo proizvode

    //proizvodi

    Route::post('store-product', [ProductController::class, 'store']);
    Route::get('view-product', [ProductController::class, 'index']);
    Route::get('edit-product/{id}', [ProductController::class, 'edit']);
    Route::post('update-product/{id}', [ProductController::class, 'update']);
});


Route::middleware(['auth:sanctum'])->group(function () {



    Route::post('logout', [AuthController::class, 'logout']);
});
