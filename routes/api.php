<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ProductController;
use Illuminate\Support\Facades\Route;

                                            // header to sent json not html
// Accept: application/json
// Content-Type: application/json

Route::get('/test', function () {
    return response()->json([
        'message' => 'API is working 🚀'
    ]);
});

// test all products and return json
//  http://127.0.0.1:8000/api/products       // get
Route::get('/products', [ProductController::class, 'index']);

// http://127.0.0.1:8000/api/products/12    // get
Route::get('/products/{product}', [ProductController::class, 'show']);



Route::post('/register', [AuthController::class, 'register']);
// http://127.0.0.1:8000/api/register            //post
// body=> row => json
// {
//   "name": "Mohamed",
//   "email": "mohamed@example.com",
//   "password": "12345678",
//   "password_confirmation": "12345678"
// }
Route::post('/login', [AuthController::class, 'login']);
// http://127.0.0.1:8000/api/login
// body=> row => json
// {
//   "email": "ah3362676@gmail.com",
//   "password": "21003542100354"
// }

// token=> "4|zj8XNXVJCoTzXnAe9DmAISh67VmvXxxbWVSfzC7655e25a2e"

Route::middleware('auth:sanctum')->group(function () {
        //cart routes will be here

    Route::get('/cart', [CartController::class, 'index']);
        // http://127.0.0.1:8000/api/cart              // get

    Route::post('/cart', [CartController::class, 'store']);
    // http://127.0.0.1:8000/api/cart              // post
//     {
//   "product_id": 1,
//   "quantity": 2
// }
    Route::put('/cart/{cartItem}', [CartController::class, 'update']);
    // http://127.0.0.1:8000/api/cart/3              // put
//     {
//   "quantity": 5
// }
    Route::delete('/cart/{cartItem}', [CartController::class, 'destroy']);
    // http://127.0.0.1:8000/api/cart/3              // delete


    //orders routes will be here
Route::post('/checkout', [OrderController::class, 'store']);
// http://127.0.0.1:8000/api/checkout              // post
// body=> row => json
// {
//   "phone": "1234567890",
//   "address": "123 Main St, City, Country",
//   "notes": "Please deliver between 5-6 PM"
// }
Route::get('/orders', [OrderController::class, 'index']);
// http://127.0.0.1:8000/api/orders              // get
Route::get('/orders/{order}', [OrderController::class, 'show']);
// http://127.0.0.1:8000/api/orders/1              // get

// token email admin in seeders or factory
    Route::get('/admin/orders', [OrderController::class, 'adminIndex']);
    // http://127.0.0.1:8000/api/admin/orders              // get
    Route::put('/admin/orders/{order}', [OrderController::class, 'updateStatus']);
    // http://127.0.0.1:8000/api/admin/orders/1             // put


    Route::post('/logout', [AuthController::class, 'logout']);
    // http://127.0.0.1:8000/api/logout              // post
});
