<?php
use Minic\Core\Config;

function site_url(string $path = ''): string {
    return rtrim(Config::get('base_url', '/'), '/') . '/' . ltrim($path, '/');
}

function static_url(string $path = ''): string {
    return site_url('static/' . ltrim($path, '/'));
}
