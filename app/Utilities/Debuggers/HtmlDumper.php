<?php

namespace App\Utilities\Debuggers;

use Symfony\Component\VarDumper\Dumper\HtmlDumper as SymfonyHtmlDumper;

class HtmlDumper extends SymfonyHtmlDumper
{
	// Styling for browser output
	protected $styles = [
		'default' => 'background-color:#fff; color:#24292e; line-height:1.428571429; font-weight:normal; font:12px Monaco, Consolas, monospace; word-wrap: break-word; white-space: pre-wrap; position:relative; z-index:100000',
		'num' => 'color:#005cc5',
		'const' => 'color:#005cc5',
		'str' => 'color:#032f62',
		'cchr' => 'color:#008000',
		'note' => 'color:#6f42c1',
		'ref' => 'color:#888',
		'public' => 'color:#d73a49',
		'protected' => 'color:#d73a49',
		'private' => 'color:#d73a49',
		'meta' => 'color:#b729d9',
		'key' => 'color:#032f62',
		'index' => 'color:#a71d5d',
	];
}