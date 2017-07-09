<?php

namespace App\Migrations;

use App\Migrations\Contracts\MigrationInterface;
use App\Models\Customer;
use pdeans\Miva\Provision\Manager as Provision;

class CustomerMigration extends Migration implements MigrationInterface
{
	protected $prv;
	protected $customer;

	public function __construct(Provision $provision, Customer $customer)
	{
		$this->prv      = $provision;
		$this->customer = $customer;
	}

	public function convert($customers)
	{
		$xml = '';

		foreach ($customers as $customer) {
			$xml .= $this->add($customer);
			$xml .= $this->update($customer);
		}

		return $xml;
	}

	protected function add($customer)
	{
		return $this->addCustomer($customer);
	}

	protected function addCustomer($customer)
	{
		return '';
	}

	protected function update($customer)
	{
		$xml = '';

		$xml .= $this->updateCustomer($customer);

		return $xml;
	}

	protected function updateCustomer($customer)
	{
		return '';
	}
}