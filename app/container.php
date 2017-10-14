<?php

use App\Migrations\CategoryMigration;
use App\Migrations\ProductMigration;
use App\Migrations\CustomerMigration;
use App\Migrations\OrderMigration;
use App\Models\Categories\Category;
use App\Models\Products\Product;
use App\Models\Customers\Customer;
use App\Models\Orders\Order;
use App\Store;
use Interop\Container\ContainerInterface;
use pdeans\Http\Client as HttpClient;
use pdeans\Miva\Provision\Manager as Provision;
use Slim\Interfaces\RouterInterface;
use Slim\Views\Twig;
use Slim\Views\TwigExtension;

return [
	CategoryMigration::class => function (ContainerInterface $c) {
		$category_migration = new CategoryMigration(
			$c->get(Provision::class),
			$c->get(Category::class)
		);

		$category_migration->setStore($c->get(Store::class));

		return $category_migration;
	},
	ProductMigration::class => function (ContainerInterface $c) {
		$product_migration = new ProductMigration(
			$c->get(Provision::class),
			$c->get(Product::class)
		);

		$product_migration->setStore($c->get(Store::class));

		return $product_migration;
	},
	CustomerMigration::class => function (ContainerInterface $c) {
		$customer_migration = new CustomerMigration(
			$c->get(Provision::class),
			$c->get(Customer::class)
		);

		$customer_migration->setStore($c->get(Store::class));

		return $customer_migration;
	},
	OrderMigration::class => function (ContainerInterface $c) {
		$order_migration = new OrderMigration(
			$c->get(Provision::class),
			$c->get(Order::class)
		);

		$order_migration->setStore($c->get(Store::class));

		return $order_migration;
	},
	Category::class => function (ContainerInterface $c) {
		return new Category;
	},
	Product::class => function (ContainerInterface $c) {
		return new Product;
	},
	Customer::class => function (ContainerInterface $c) {
		return new Customer;
	},
	Order::class => function (ContainerInterface $c) {
		return new Order;
	},
	Store::class => function (ContainerInterface $c) {
		return new Store([
			'url'      => $c->get('store.url'),
			'code'     => $c->get('store.code'),
			'root'     => $c->get('store.root'),
			'graphics' => $c->get('store.graphics'),
		]);
	},
	Provision::class => function (ContainerInterface $c) {
		return new Provision(
			$c->get('store.code'),
			$c->get('provision.url'),
			$c->get('provision.token')
		);
	},
	RouterInterface::class => function (ContainerInterface $c) {
		return $c->get('router');
	},
	Twig::class => function (ContainerInterface $c) {
		$twig = new Twig(VIEW_PATH, [
			'cache' => false,
		]);

		$twig->addExtension(
			new TwigExtension($c->get('router'), $c->get('request')->getUri())
		);

		return $twig;
	},
	HttpClient::class => function (ContainerInterface $c) {
		return new HttpClient;
	},
];