<?php

namespace App\Miva\Conversions\Contracts;

interface ConversionInterface
{
    /**
     * Converts \Traversable|array items into remote
     * provisioning xml
     *
     * @param \Traversable|array  $items  Conversion items
     * @return string  Remote provisioning xml
     */
    public function convert($items);
}
