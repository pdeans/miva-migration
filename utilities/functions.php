<?php

// Debug print function
function dp($data, $label = '')
{
	echo ($label !== '' ? $label.'<br>' : ''), '<pre>', (is_array($data) || is_object($data) ? print_r($data) : $data), '</pre><br>';
}

// Debug print function for XML
function dpx($data, $label = '')
{
	echo ($label !== '' ? $label.'<br>' : ''), '<pre>', htmlentities($data), '</pre><br>';
}