<?php

use App\Utilities\Debuggers\Dumper;

// Debug print function
function dp($value, $label = '')
{
	(new Dumper)->dump($value, $label);
}

// Debug print (old) function -- is deprecrated in place of Symfony var-dumper
function dpo($data, $label = '')
{
	echo ($label !== '' ? $label.'<br>' : ''), '<pre>', (is_array($data) || is_object($data) ? print_r($data) : $data), '</pre><br>';
}

// Debug print function for XML
function dpx($data, $label = '')
{
	echo ($label !== '' ? $label.'<br>' : ''), '<pre>', htmlentities($data), '</pre><br>';
}