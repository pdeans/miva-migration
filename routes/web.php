<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------

/**
 * Home Routes
 */
Route::get('/', 'HomeController@index')->name('home.index');

/**
 * Category Routes
 */
Route::prefix('categories')->group(function () {
    Route::get('/', 'CategoryController@index')->name('category.index');
    Route::get('/convert', 'CategoryController@convert')->name('category.convert');
});

/**
 * Product Routes
 */
Route::prefix('products')->group(function () {
    Route::get('/', 'ProductController@index')->name('product.index');
    Route::get('/convert', 'ProductController@convert')->name('product.convert');
});

/**
 * Customer Routes
 */
Route::prefix('customers')->group(function () {
    Route::get('/', 'CustomerController@index')->name('customer.index');
    Route::get('/convert', 'CustomerController@convert')->name('customer.convert');
});

/**
 * Order Routes
 */
Route::prefix('orders')->group(function () {
    Route::get('/', 'OrderController@index')->name('order.index');
    Route::get('/convert', 'OrderController@convert')->name('order.convert');
});
