<?php

namespace App\Http\Conversions;

use App\Http\Conversions\Contracts\ConversionInterface;
use App\Miva\Store;
use App\Models\Categories\Category;
use pdeans\Miva\Provision\Manager as Provision;

class CategoryConversion extends Conversion implements ConversionInterface
{
	protected $prv;
	protected $category;

	public function __construct(Store $store, Provision $provision, Category $category)
	{
		$this->setStore($store);

		$this->prv      = $provision;
		$this->category = $category;
	}

	public function convert($categories)
	{
		$xml = '';

		foreach ($categories as $category) {
			$xml .= $this->addCategory($category);
			$xml .= $this->updateCategory($category);
		}

		return $xml;
	}

	protected function addCategory($category)
	{
		return '';
	}

	protected function updateCategory($category)
	{
		return '';
	}
}