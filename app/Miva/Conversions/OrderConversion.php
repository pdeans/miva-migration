<?php

namespace App\Miva\Conversions;

use App\Miva\Conversions\Contracts\ConversionInterface;
use App\Miva\Store;
use App\Models\Order;
use pdeans\Miva\Api\Manager as Api;

class OrderConversion extends Conversion implements ConversionInterface
{
    protected $api;
    protected $order;

    public function __construct(Store $store, Api $api, Order $order)
    {
        $this->setStore($store);

        $this->api   = $api;
        $this->order = $order;
    }

    public function convert($orders)
    {
        foreach ($orders as $order) {
            $this->addOrder($order);
            $this->updateOrder($order);
        }

        return clone $this->api;
    }

    protected function addOrder($order)
    {
        // return $this->api->func('Order_Create')
        //     ->params([
        //         'Order_Id' => 123,
        //     ])
        //     ->add();
    }

    protected function updateOrder($order)
    {
        return null;
    }
}
