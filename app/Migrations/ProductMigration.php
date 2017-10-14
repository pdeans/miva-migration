<?php

namespace App\Migrations;

use App\Migrations\Contracts\MigrationInterface;
use App\Models\Products\Product;
use pdeans\Miva\Provision\Manager as Provision;

class ProductMigration extends Migration implements MigrationInterface
{
	protected $prv;
	protected $product;

	public function __construct(Provision $provision, Product $product)
	{
		$this->prv     = $provision;
		$this->product = $product;
	}

	public function convert($products)
	{
		$xml = '';

		foreach ($products as $product) {
			$xml .= $this->add($product);
			$xml .= $this->update($product);
		}

		return $xml;
	}

	protected function add($product)
	{
		return $this->addProduct($product);
	}

	protected function addProduct($product)
	{
		return '';
	}

	protected function update($product)
	{
		$xml = '';

		$xml .= $this->updateProduct($product);

		return $xml;
	}

	protected function updateProduct($product)
	{
		return '';
	}
}