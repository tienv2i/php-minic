<?php

define('DS', DIRECTORY_SEPARATOR);
define('BASE_PATH', __DIR__);

include 'bootstrap.php';
use App\Bootstrap;

Bootstrap::setup([
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
])
->get('/', function(Bootstrap $app) {
    $app->render('home.php', [
        'message' => 'Hello world'
    ]);
})
->get('/hello/{word}', function(Bootstrap $app, $params) {
    $app->render('home.php', [
        'message' => 'Hello '.$params[0]
    ]);
})
// Trang danh sách file và form upload
->get('/files', function(Bootstrap $app) {
    $files = getUploadedFiles($app->getConfig('uploaded_dir'));
    $app->render('files_list.php', ['files' => $files]);
})

// Xử lý upload file
->post('/files/upload', function(Bootstrap $app) {
    $result = handleFileUpload($app->getConfig('uploaded_dir'));
    header("Location: /files");
    exit;
})

// Hiển thị file đã upload
->get('/files/{file_name}', function(Bootstrap $app, $params) {
    $file_name = basename($params[0]);
    $file_path = rtrim($app->getConfig('uploaded_dir'), '/') . '/' . $file_name;

    if (!file_exists($file_path)) {
        http_response_code(404);
        echo "File not found";
        return;
    }

    header("Content-Type: " . mime_content_type($file_path));
    readfile($file_path);
})
->runApp();
