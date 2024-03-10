<?php
use App\Http\Controllers\ProductsController;


Route::apiResource('products', ProductsController::class);
Route::post('/products/{product}/approval',[ProductsController::class,'approval'])->middleware('is_admin');
