<?php

namespace App\Http\Conversions;

use App\Http\Conversions\Contracts\ConversionInterface;
use App\Miva\Store;
use App\Models\Products\Product;
use pdeans\Miva\Provision\Manager as Provision;

class ProductConversion extends Conversion implements ConversionInterface
{
	protected $prv;
	protected $product;

	public function __construct(Store $store, Provision $provision, Product $product)
	{
		$this->setStore($store);

		$this->prv     = $provision;
		$this->product = $product;
	}

	public function convert($products)
	{
		$xml = '';

		foreach ($products as $product) {
			$xml .= $this->addProduct($product);
			$xml .= $this->updateProduct($product);
		}

		return $xml;
	}

	protected function addProduct($product)
	{
		return '';
	}

	protected function updateProduct($product)
	{
		return '';
	}
}