<?php

use App\App;
use Dotenv\Dotenv;
use Illuminate\Database\Capsule\Manager as Capsule;

require_once BASE_PATH.'/vendor/autoload.php';

$dotenv = new Dotenv(BASE_PATH);
$dotenv->load();

$app = new App;

$db_config = require_once CONFIG_PATH.'/database.php';

$dbh = new Capsule;
$dbh->addConnection($db_config);
$dbh->setAsGlobal();
$dbh->bootEloquent();

require_once APP_PATH.'/routes.php';