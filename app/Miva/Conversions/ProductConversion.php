<?php

namespace App\Miva\Conversions;

use App\Miva\Conversions\Contracts\ConversionInterface;
use App\Miva\Store;
use App\Models\Product;
use pdeans\Miva\Api\Manager as Api;

class ProductConversion extends Conversion implements ConversionInterface
{
    protected $api;
    protected $product;

    public function __construct(Store $store, Api $api, Product $product)
    {
        $this->setStore($store);

        $this->api     = $api;
        $this->product = $product;
    }

    public function convert($products)
    {
        foreach ($products as $product) {
            $this->addProduct($product);
            $this->updateProduct($product);
        }

        return clone $this->api;
    }

    protected function addProduct($product)
    {
        // return $this->api->func('Product_Insert')
        //     ->params([
        //         'Product_Code' => 'prodcode1',
        //         'Product_Name' => 'prod name 1',
        //     ])
        //     ->add();
    }

    protected function updateProduct($product)
    {
        // return $this->api->func('Product_Update')
        //     ->params([
        //         'Product_Code' => 'prodcode1',
        //         'Product_Name' => 'prod name 1',
        //     ])
        //     ->add();
    }
}
