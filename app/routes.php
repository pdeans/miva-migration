<?php

/**
 * Home Routes
 */
$app->get('/', ['App\Controllers\HomeController', 'index'])->setName('home');

/**
 * Category Routes
 */
$app->group('/categories', function () {
	$this->get('', ['App\Controllers\CategoryController', 'index'])->setName('category.index');
	$this->get('/convert', ['App\Controllers\CategoryController', 'convert'])->setName('category.convert');
});

/**
 * Product Routes
 */
$app->group('/products', function () {
	$this->get('', ['App\Controllers\ProductController', 'index'])->setName('product.index');
	$this->get('/convert', ['App\Controllers\ProductController', 'convert'])->setName('product.convert');
});

/**
 * Customer Routes
 */
$app->group('/customers', function () {
	$this->get('', ['App\Controllers\CustomerController', 'index'])->setName('customer.index');
	$this->get('/convert', ['App\Controllers\CustomerController', 'convert'])->setName('customer.convert');
});

/**
 * Order Routes
 */
$app->group('/orders', function () {
	$this->get('', ['App\Controllers\OrderController', 'index'])->setName('order.index');
	$this->get('/convert', ['App\Controllers\OrderController', 'convert'])->setName('order.convert');
});
