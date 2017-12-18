<?php

namespace App\Http\Conversions;

use App\Http\Conversions\Contracts\ConversionInterface;
use App\Miva\Store;
use App\Models\Customers\Customer;
use pdeans\Miva\Provision\Manager as Provision;

class CustomerConversion extends Conversion implements ConversionInterface
{
	protected $prv;
	protected $customer;

	public function __construct(Store $store, Provision $provision, Customer $customer)
	{
		$this->setStore($store);

		$this->prv      = $provision;
		$this->customer = $customer;
	}

	public function convert($customers)
	{
		$xml = '';

		foreach ($customers as $customer) {
			$xml .= $this->addCustomer($customer);
			$xml .= $this->updateCustomer($customer);
		}

		return $xml;
	}

	protected function addCustomer($customer)
	{
		return '';
	}

	protected function updateCustomer($customer)
	{
		return '';
	}
}