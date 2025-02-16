PHP Mini Framework

Overview

This is a lightweight PHP micro-framework designed for handling routing, rendering views, and providing a minimalistic approach to web application development.

Features

Simple routing system (GET, POST, PUT, DELETE)

View rendering with default variables

Hook system for event-based execution

Response handling (JSON, text, HTML)

Logging and debugging utilities

Installation

Clone the repository or copy the source files into your project directory.

Ensure your server supports PHP 7.4+.

Set up your document root to public/ if using Apache or configure your server accordingly.

Usage

Setting up the framework

use App\Bootstrap;

Bootstrap::setup([
    "view_defaults" => [
        "site_title" => "My PHP Framework",
        "base_url" => "/",
        "static_url" => "/public/static"
    ],
    "view_dir" => __DIR__."/views",
    "hooks" => [
        "before_dispatch" => function (Bootstrap $app) {
            // Custom logic before dispatching routes
        }
    ]
])->get('/', function(Bootstrap $app) {
    $app->render('home.php', [
        'message' => 'Hello world!'
    ]);
})->runApp();

Defining Routes

$app->get('/user/{slug}', function($app, $params) {
    echo "User profile: " . $params[0];
});

$app->post('/submit', function($app, $params) {
    echo "Form submitted";
});

Rendering Views

$app->render('home.php', [
    'message' => 'Welcome to my site!'
]);

File Structure

/project-root
│── public/
│   │── index.php
│   │── static/
│── views/
│   │── home.php
│── src/
│   │── Bootstrap.php
│── README.md

License

This project is open-source and available under the MIT license.