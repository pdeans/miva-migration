<?php

namespace App\Utilities\Debuggers;

use Symfony\Component\VarDumper\Cloner\VarCloner;
use Symfony\Component\VarDumper\Dumper\CliDumper;

class Dumper
{
	public function dump($value, $label = '')
	{
		$is_cli = (in_array(PHP_SAPI, ['cli', 'phpdbg']) ? true : false);

		if ($label !== '') {
			echo $label, ($is_cli ? PHP_EOL : '<br>');
		}

		if (class_exists(CliDumper::class)) {
			$dumper = ($is_cli ? new CliDumper : new HtmlDumper);

			$dumper->dump((new VarCloner)->cloneVar($value));
		}
		else {
			var_dump($value);
		}
	}
}