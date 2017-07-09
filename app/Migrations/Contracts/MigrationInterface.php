<?php

namespace App\Migrations\Contracts;

interface MigrationInterface
{
	/**
	 * Converts \Traversable|array items into remote
	 * provisioning xml
	 *
	 * @param  \Traversable|array $items Conversion items
	 * @return string Remote provisioning xml
	 */
	public function convert($items);
}