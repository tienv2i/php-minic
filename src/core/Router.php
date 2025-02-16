<?php
namespace Minic\Core;

class Router extends Singleton{
    
    public static function dispatch() {
        $request = Request::getInstance();
    
        $controllerName = str_replace(' ', '', ucwords(str_replace('_', ' ', $request->getController())));
        $controllerClass = "Minic\\Controller\\$controllerName";
    
        if (!class_exists($controllerClass)) {
            return Response::send("404 Controller Not Found: $controllerClass", 404);
        }
    
        $controller = new $controllerClass();
        $baseAction = str_replace(' ', '', ucwords(str_replace('_', ' ', $request->getAction())));
        $methodSuffix = strtolower($request->getMethod());
    
        $possibleActions = [
            $baseAction,                    // index()
            "{$baseAction}__$methodSuffix",  // index__post()
            "{$baseAction}__{$methodSuffix}" // index__post()
        ];
    
        foreach ($possibleActions as $action) {
            if (method_exists($controller, $action)) {
                return call_user_func_array([$controller, $action], $request->getParams());
            }
        }
    
        return Response::send("404 Action Not Found: $controllerClass::{$baseAction}", 404);
    }
    
    
}