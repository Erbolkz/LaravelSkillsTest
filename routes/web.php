<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\MainController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [MainController::class, 'home']);

Route::get('/products', [MainController::class, 'products']);

Route::get('/products/fetch', [MainController::class, 'products_fetch']);

Route::get('/products/{id}', [MainController::class, 'get_product']);

Route::post('/products/add', [MainController::class, 'product_add']);

Route::post('/products/edit', [MainController::class, 'product_edit']);

Route::post('/products/del', [MainController::class, 'product_del']);





