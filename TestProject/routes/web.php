<?php

use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});



Route::get('index2',[OrderController::class,'index2']);
Route::get('index/{id}',[OrderController::class,'index']);
Route::post('createbill',[OrderController::class,'createbill']);
Route::post('store/{order}',[OrderController::class,'store'])->name('store');


Route::controller(ProductController::class)->group(function(){

    Route::get('products','index');

});

