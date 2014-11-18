<?php
/**
 * @author Stanislav Vetlovskiy
 * @date 19.11.2014
 */

error_reporting(7);
error_reporting (E_ALL);

require_once __DIR__ . '/../../vendor/autoload.php';
$app = require_once __DIR__ . '/../init.php';

return Doctrine\ORM\Tools\Console\ConsoleRunner::createHelperSet($app['orm.em']);
