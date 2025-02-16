<?php

define('DS', DIRECTORY_SEPARATOR);
define('BASE_PATH', __DIR__);

include 'vendor/autoload.php';

use Minic\Core\Bootstrap;
use Minic\Core\Config;


Config::initialize([
    'app_name'   => 'My App',
    'base_url'   => 'http://localhost/myapp',
    'twig_cache' => false, 
    'twig_debug' => true,  
]);

Config::set('twig_helpers', [
    'base_url' => 'base_url',  
    'asset' => 'asset_url',    
    'session_get' => 'session_get',
    'view_ext' => '.twig',
]);

Config::set('views_dir', __DIR__ . '/src/views');
Config::set('twig_cache', false);
Config::set('twig_debug', true);
Config::set('app_name', 'My Mini Framework');
Config::set('base_url', 'http://localhost/myapp');

Bootstrap::getInstance()->runApp();


