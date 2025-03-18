<?php
namespace App;

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/config/config.php';

use App\Application;

if ($config['displayErrors']) {
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
    error_reporting(E_ALL);
}

// TODO вынести в отдельный файл
$env = parse_ini_file('.env');
foreach ($env as $key => $value) {
    putenv("$key=$value");
}

$app = new Application($config);
return $app->run();
