<?php

define('DS', DIRECTORY_SEPARATOR);
define('BASE_PATH', __DIR__);

include 'vendor/autoload.php';

use Minic\Core\Bootstrap;
use Minic\Core\Config;


Config::initialize([
    'app_name'   => 'My App',
    'base_url'   => 'http://localhost:8000',
    'static_url' => '/static',
    'twig_cache' => false, 
    'twig_debug' => true,  
    'views_dir' => __DIR__ . '/src/views',
    'helpers_dir' => __DIR__ . '/src/helpers'
]);

Bootstrap::getInstance()->runApp();


