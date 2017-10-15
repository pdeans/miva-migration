<?php

require_once __DIR__.'/../paths.php';
require_once BASE_PATH.'/bootstrap/app.php';
require_once UTILS_PATH.'/debuggers.php';
# Uncomment below if adding global app functions to utilities/functions.php
// require_once UTILS_PATH.'/functions.php';

$app->run();