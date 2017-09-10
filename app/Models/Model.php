<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model as EloquentModel;

abstract class Model extends EloquentModel
{
	public function generateCode($value, $separator = '-')
	{
		$value = trim($value);

		$find = [
			'/[^\w\-]+/',
			'/[\-]{2,}/',
			'/[\_]{2,}/',
			'/\_\-\_/',
			'/\-\_\-/',
		];

		$replace = [
			$separator,
			'-',
			'_',
			$separator,
			$separator,
		];

		$code = preg_replace($find, $replace, $value);

		return strtolower(trim(substr($code, 0, self::$MAX_CODE_LENGTH), '-_'));
	}
}