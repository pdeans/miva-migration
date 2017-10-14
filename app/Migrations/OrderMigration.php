<?php

namespace App\Migrations;

use App\Migrations\Contracts\MigrationInterface;
use App\Models\Orders\Order;
use pdeans\Miva\Provision\Manager as Provision;

class OrderMigration extends Migration implements MigrationInterface
{
	protected $prv;
	protected $order;

	public function __construct(Provision $provision, Order $order)
	{
		$this->prv   = $provision;
		$this->order = $order;
	}

	public function convert($orders)
	{
		$xml = '';

		foreach ($orders as $order) {
			$xml .= $this->add($order);
			$xml .= $this->update($order);
		}

		return $xml;
	}

	protected function add($order)
	{
		return $this->addOrder($order);
	}

	protected function addOrder($order)
	{
		return '';
	}

	protected function update($order)
	{
		$xml = '';

		$xml .= $this->updateOrder($order);

		return $xml;
	}

	protected function updateOrder($order)
	{
		return '';
	}
}