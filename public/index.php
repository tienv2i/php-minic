<?php

define('DS', DIRECTORY_SEPARATOR);
define('BASE_PATH', realpath(__DIR__.DS.'..'));

include '../bootstrap.php';
use App\Bootstrap;

Bootstrap::setup([
    "view_defaults" => [
        "site_title" => "Huynh AT's minic framework",
        "base_url" => "/",
        "static_url" => "/public/static"
    ],
    "view_dir" => BASE_PATH.DS."views",
    "hooks" => [
        "before_dispatch" => function (Bootstrap $app) {
            
        },
        "after_bootstrap_construct" => function (Bootstrap $app) {
            
        }
    ]
])->get('/', function(Bootstrap $app) {
    $app->render('home.php', [
        'message' => 'Hello world'
    ]);
})->runApp();
