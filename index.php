<?php

define('DS', DIRECTORY_SEPARATOR);
define('BASE_PATH', __DIR__);

include 'vendor/autoload.php';

use Minic\Core\Bootstrap;
use Minic\Core\Config;

$config = [
    "view_defaults" => [
        "site_title" => "Huynh AT's minic framework",

        "static_url" => "/static"
    ],
    "base_url" => "/index.php",
    "view_dir" => BASE_PATH.DS."views",
    "uploaded_dir" => BASE_PATH.DS."uploaded",
    "uploaded_url" => "/uploaded",
    "helper_dir" => BASE_PATH.DS."helpers",
    "helpers" => [
        "url",
        "uploader",
    ],
    "hooks" => [
        "before_dispatch" => function (Bootstrap $app) {
            
        },
        "after_bootstrap_construct" => function (Bootstrap $app) {
            
        }
    ]
];

Config::initialize($config);

Bootstrap::getInstance()->runApp();


