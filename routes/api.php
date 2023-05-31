<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckOutController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\WishListController;
use App\Http\Controllers\ProductController;

use App\Http\Controllers\ReviewController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/logout', [AuthController::class, 'logout']);

    //EmailVerification
    Route::post('/email_verification', [EmailVerificationController::class, 'email_verification']);
    Route::get('/sendEmailVerification', [EmailVerificationController::class, 'sendEmailVerification']);

});

// Admins only routes
// Route::group([
//     'prefix' => 'admin',
//     'middleware' => ['api', 'auth', 'is.admin'],
// ], function () {
//     Route::get('posts', [ProductController::class, 'index']);
// });

###########################################################################################

Route::middleware('auth')->post('/reviews', [ReviewController::class, 'store']);

//Route::post('/products', [ProductController::class, 'store']);



##########################################################################################
Route::post('/review', [ReviewController::class, 'store']);


#########################################################################################
Route::group([
    'prefix' => 'seller',
         'middleware' => ['api', 'auth', 'is.seller'],
     ], function () {
        Route::post('/products', [ProductController::class, 'store']);
        Route::post('/products/{id}', [ProductController::class, 'update']);
        Route::get('/delete/{id}', [ProductController::class, 'delete']);

        Route::get('/{id}/products', [ProductController::class, 'sellerProducts']);

    });
########################################################################################
Route::group([
    'prefix' => 'customer',
         'middleware' => ['api', 'auth', 'is.customer'],
     ], function () {

        Route::post('/cart', [CartController::class, 'store']);
        Route::get('/cart', [CartController::class, 'index']);
        Route::delete('/cart/{id}', [CartController::class, 'delete']);
########################################################################################
        Route::post('/wishlist', [WishListController::class, 'store']);
        Route::get('/wishlist', [WishListController::class, 'index']);
        Route::delete('/wishlist/{id}', [WishListController::class, 'delete']);
        
        Route::post('/orders', [CheckOutController::class, 'checkout']);

    });
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{id}', [ProductController::class, 'show']);
Route::get('/products/{id}/relatedProducts', [ProductController::class, 'relatedProducts']);


//Mail Route
Route::post('/contact', [ContactController::class, 'submit']);




