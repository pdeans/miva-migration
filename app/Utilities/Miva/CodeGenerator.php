<?php

namespace App\Utilities\Miva;

class CodeGenerator
{
	protected $max_length;
	protected $separator;
	protected $case;

	public function __construct($max_length = 50, $separator = '-', $case = 'none')
	{
		$this->setMaxLength($max_length);
		$this->setSeparator($separator);
		$this->setCase($case);
	}

	public function setMaxLength($max_length)
	{
		$this->max_length = (int)$max_length;

		return $this;
	}

	public function getMaxLength()
	{
		return $this->max_length;
	}

	public function setSeparator($separator)
	{
		if (preg_match('/[^\w-]+/', $separator)) {
			throw new Exception("Invalid separator value '$separator'. Valid separators: 'a-z', 'A-Z', '0-9', '_', or '-'.");
		}

		$this->separator = $separator;

		return $this;
	}

	public function getSeparator()
	{
		return $this->separator;
	}

	public function setCase($case)
	{
		$case = strtolower($case);

		$valid_case_values = [
			'none',
			'lowercase',
			'uppercase',
		];

		if (!in_array($case, $valid_case_values)) {
			throw new Exception("Invalid case value '$case'. Valid case values: ".implode(', ', $valid_case_values));
		}

		$this->case = $case;

		return $this;
	}

	public function getCase()
	{
		return $this->case;
	}

	public function create($value)
	{
		$find = [
			'/[^\w\-]+/',
			'/[\-]{2,}/',
			'/[\_]{2,}/',
			'/\_\-\_/',
			'/\-\_\-/',
		];

		$replace = [
			$this->separator,
			'-',
			'_',
			$this->separator,
			$this->separator,
		];

		$code = preg_replace('/^[^a-z\d]+|[^a-z\d]+$/i', '', substr(preg_replace($find, $replace, trim($value)), 0, $this->getMaxLength()));

		if ($this->case === 'lowercase') {
			return strtolower($code);
		}
		else if ($this->case === 'uppercase') {
			return strtoupper($code);
		}

		return $code;
	}
}