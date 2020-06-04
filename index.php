<?php

require_once 'bootstrap/app.php';
require_once 'app/routes.php';
error_reporting(E_ALL);
ini_set('display_errors', 'on');

// Run app
$app->run();