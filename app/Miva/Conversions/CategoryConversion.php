<?php

namespace App\Miva\Conversions;

use App\Miva\Conversions\Contracts\ConversionInterface;
use App\Miva\Store;
use App\Models\Category;
use pdeans\Miva\Api\Manager as Api;

class CategoryConversion extends Conversion implements ConversionInterface
{
    protected $api;
    protected $category;

    public function __construct(Store $store, Api $api, Category $category)
    {
        $this->setStore($store);

        $this->api      = $api;
        $this->category = $category;
    }

    public function convert($categories)
    {
        foreach ($categories as $category) {
            $this->addCategory($category);
            $this->updateCategory($category);
        }

        return clone $this->api;
    }

    protected function addCategory($category)
    {
        // return $this->api->func('Category_Insert')
        //     ->params([
        //         'Category_Code' => 'catcode1',
        //         'Category_Name' => 'cat name 1',
        //     ])
        //     ->add();
    }

    protected function updateCategory($category)
    {
        // return $this->api->func('Category_Update')
        //     ->params([
        //         'Category_Code' => 'catcode1',
        //         'Category_Name' => 'cat name 1',
        //     ])
        //     ->add();
    }
}
