<?php
include 'minic.php';
define('DS', DIRECTORY_SEPARATOR);

$app = Minic::setup([
    'base_dir' => __DIR__,
    'template_dir' => __DIR__ . DS . 'templates',
    'base_url' => '/',
    'static_url' => '/static',
    'page_title' => 'My Simple App'
]);

$app
    ->route('GET', '/', function ($app, $params) {
        $app->render('home', ['message' => 'Welcome to php-minic!']);
    })
    ->route('GET', '/about', function ($app, $params) {
        $app->render('about', ['page_title' => 'About Us']);
    })
    ->route('GET', '/api/json', function ($app, $params) {
        $app->response_json(['status' => 'success', 'data' => 'Hello, JSON!']);
    })
    ->route('POST', '/submit', function ($app, $params) {
        $app->response_text("Form submitted successfully!");
    })
    ->route('GET', '*', function ($app, $params) {
        $app->response_404("Page Not Found");
    })
    ->dispatch();
