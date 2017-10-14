<?php

namespace App\Migrations;

use App\Migrations\Contracts\MigrationInterface;
use App\Models\Categories\Category;
use pdeans\Miva\Provision\Manager as Provision;

class CategoryMigration extends Migration implements MigrationInterface
{
	protected $prv;
	protected $category;

	public function __construct(Provision $provision, Category $category)
	{
		$this->prv      = $provision;
		$this->category = $category;
	}

	public function convert($categories)
	{
		$xml = '';

		foreach ($categories as $category) {
			$xml .= $this->add($category);
			$xml .= $this->update($category);
		}

		return $xml;
	}

	protected function add($category)
	{
		return $this->addCategory($category);
	}

	protected function addCategory($category)
	{
		return '';
	}

	protected function update($category)
	{
		$xml = '';

		$xml .= $this->updateCategory($category);

		return $xml;
	}

	protected function updateCategory($category)
	{
		return '';
	}
}