<?php
define('BASE_PATH', __DIR__);
define('DS', DIRECTORY_SEPARATOR);

include 'bootstrap.php';
use App\Bootstrap;

Bootstrap::setup([
    "site_title" => "Huynh AT's minic framework",
    "view_dir" => "./views",
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
