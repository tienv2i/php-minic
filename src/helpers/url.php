<?php
use Minic\Core\Config;

function base_url(string $path = ''): string {
    return rtrim(Config::get('base_url', '/'), '/') . '/' . ltrim($path, '/');
}

function asset_url(string $path = ''): string {
    return base_url('assets/' . ltrim($path, '/'));
}
