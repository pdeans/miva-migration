<?php

use pdeans\Debuggers\Vardumper\Dumper;

// Debug print function
function dp($value, $label = '')
{
	(new Dumper)->dump($value, $label);
}

// Debug print function for XML
function dpx($xml, $label = '')
{
	echo ($label !== '' ? $label.'<br>' : ''), '<pre>', htmlentities($xml), '</pre><br>';
}

// Debug print function (deprecrated)
function dpo($value, $label = '')
{
	echo ($label !== '' ? $label.'<br>' : ''),'<pre>', (is_array($value) || is_object($value) ? print_r($value) : $value), '</pre><br>';
}