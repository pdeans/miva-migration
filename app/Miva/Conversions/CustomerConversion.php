<?php

namespace App\Miva\Conversions;

use App\Miva\Conversions\Contracts\ConversionInterface;
use App\Miva\Store;
use App\Models\Customer;
use pdeans\Miva\Api\Manager as Api;

class CustomerConversion extends Conversion implements ConversionInterface
{
    protected $api;
    protected $customer;

    public function __construct(Store $store, Api $api, Customer $customer)
    {
        $this->setStore($store);

        $this->api      = $api;
        $this->customer = $customer;
    }

    public function convert($customers)
    {
        foreach ($customers as $customer) {
            $this->addCustomer($customer);
            $this->updateCustomer($customer);
        }

        return clone $this->api;
    }

    protected function addCustomer($customer)
    {
        // return $this->api->func('Customer_Insert')
        //     ->params([
        //         'Customer_Login' => 'custlogin1',
        //         'Customer_PasswordEmail' => 'cust@email.com',
        //         'Customer_Password' => 'pass123',
        //     ])
        //     ->add();
    }

    protected function updateCustomer($customer)
    {
        // return $this->api->func('Customer_Update')
        //     ->params([
        //         'Customer_Login' => 'custlogin1',
        //         'Customer_ShipFirstName' => 'Test',
        //         'Customer_ShipLastName' => 'Order',
        //     ])
        //     ->add();
    }
}
