<?php

namespace App\Http\Conversions;

use App\Http\Conversions\Contracts\ConversionInterface;
use App\Miva\Store;
use App\Models\Orders\Order;
use pdeans\Miva\Provision\Manager as Provision;

class OrderConversion extends Conversion implements ConversionInterface
{
	protected $prv;
	protected $order;

	public function __construct(Store $store, Provision $provision, Order $order)
	{
		$this->setStore($store);

		$this->prv   = $provision;
		$this->order = $order;
	}

	public function convert($orders)
	{
		$xml = '';

		foreach ($orders as $order) {
			$xml .= $this->addOrder($order);
			$xml .= $this->updateOrder($order);
		}

		return $xml;
	}

	protected function addOrder($order)
	{
		return '';
	}

	protected function updateOrder($order)
	{
		return '';
	}
}