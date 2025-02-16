<?php

namespace Minic\Core;

use Minic\Core\Http\Request;
use Minic\Core\Http\Response;
use Minic\Core\Http\Router;

class Bootstrap extends Singleton {
    private static ?Bootstrap $instance = null;

    public function runApp() {
        Router::dispatch();
    }
}