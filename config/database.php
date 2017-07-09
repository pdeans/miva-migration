<?php

/**
 * If using Illuminate/Database, this array maps to the
 * array settings for the addConnection() method.
 *
 * See https://github.com/illuminate/database for docs.
 */
return [
	'driver'   => env('DB_DRIVER'),
	'host'     => env('DB_HOST'),
	'port'     => env('DB_PORT'),
	'database' => env('DB_DATABASE'),
	'username' => env('DB_USERNAME'),
	'password' => env('DB_PASSWORD'),
];