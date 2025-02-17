<?php
use Minic\Core\Config;
use Minic\Core\View;

function site_url(string $path = ''): string {
    return rtrim(Config::get('base_url', '/'), '/') . '/' . ltrim($path, '/');
}

function static_url(string $path = ''): string {
    return site_url('static/' . ltrim($path, '/'));
}
View::registerFunction([
    'static_url' => 'static_url',
    'site_url' => 'site_url',
]);
