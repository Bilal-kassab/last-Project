<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('register',[UserController::class,'register']);
Route::post('registerAdmin',[UserController::class,'registerAdmin']);
Route::post('login',[UserController::class,'login'])->name('login');




Route::group(['middleware'=>['auth:sanctum']],function(){

    Route::post('logout',[UserController::class,'logout']);
    Route::get('profile',[UserController::class,'profile']);
    Route::post('editeprofile',[UserController::class,'edite']);

         Route::controller(OrderController::class)->group(function(){
            Route::post('createbill','createbill');
            Route::post('add_order','store2');
            Route::post('deletedrder','DeleteOrder');
            Route::get('orderdeatiles/{id}','orderDeatiles');
            Route::post('orders','orders');
        });

        Route::controller(ProductController::class)->group(function(){
            Route::post('search','search');
            Route::post('productcategories','productCategories');
            Route::get('products','index');
            Route::get('productdetails/{id}','product_details');
        });


        Route::controller(CategoryController::class)->group(function(){
            Route::get('showcategories','showCategories');
        });
        
});

Route::middleware(['auth:sanctum','is_admin'])->group(function(){

    Route::controller(ProductController::class)->group(function(){
        Route::post('add_product','createProduct');
        Route::post('editprice','editPrice');
        Route::post('editamount','editAmount');

    });

    Route::controller(CategoryController::class)->group(function(){
        Route::post('createcategory','createCategory');
    });

    Route::controller(OrderController::class)->group(function(){
       
        Route::post('status','status');
        Route::post('paymentstatus','PaymentStatus');
        Route::get('allorders','allOrders');
    });

});


// Route::controller(OrderController::class)->group(function(){
       
//     //Route::get('DeleteOrder/{id}','DeleteOrder');
    
//     Route::get('orders','orders');
//     Route::get('allorders','allOrders');
// });


