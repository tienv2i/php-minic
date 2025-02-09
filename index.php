<?php
include 'minic.php';
define('DS', DIRECTORY_SEPARATOR);

Minic::setup([
    'base_dir' => __DIR__,
    'template_dir' => __DIR__.DS.'templates',
    'base_url' => '/',
    'static_url' => '/static'
])
->route('GET', '/', function ($app, $params) {
    $app->render('uploader',[]);
})
->route('GET', '*', function ($app, $params) {
    $app->response_text('DUMPING');
})
->dispatch();