# php-minic

A minimalistic PHP micro-framework for lightweight applications and rapid prototyping.

## Features
- Simple routing system
- Lightweight and fast
- Supports JSON, text, and HTML responses
- Basic templating support

## Usage
### Basic `index.php`
```php
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
```

### Basic Template (`templates/home.php`)
```php
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?=$static_url?>/bootstrap.min.css">
    <title><?=$this->get_config('page_title', 'Home Page')?></title>
</head>
<body>
    <h1>Welcome to php-minic!</h1>
    <p><?=$message?></p>
    <script src="<?=$static_url?>/bootstrap.bundle.min.js"></script>
</body>
</html>
```

## Routing
You can define routes using:
```php
$app->route('METHOD', '/path', function ($app, $params) {
    // Handle request
});
```
Shortcuts:
```php
$app->get('/example', function ($app, $params) {
    $app->response_text("GET request");
});
$app->post('/example', function ($app, $params) {
    $app->response_text("POST request");
});
```

## Responses
- `$app->response_text("Hello World");`
- `$app->response_json(["key" => "value"]);`
- `$app->response_html("<h1>Hello</h1>");`

## Templates
Render a template:
```php
$app->render('template_name', ['key' => 'value']);
```

## 404 Handling
```php
$app->response_404("Page Not Found");
```

---
Enjoy coding with `php-minic`! 🚀

