<?php
/**
 * @author Stanislav Vetlovskiy
 * @date 18.11.2014
 */

error_reporting(7);
error_reporting (E_ALL);

$loader = require_once __DIR__.'/../vendor/autoload.php';
$app = require_once 'init.php';

$app->run();
