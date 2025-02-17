<?php
namespace Minic\Core;

class Router extends Singleton {

    public static function dispatch() {
        $request = Request::getInstance();

        // Check URL validity (controller and action)
        if (!self::isValidUrl($request->getController()) || !self::isValidUrl($request->getAction())) {
            return Response::sendError('404', [
                'message' => 'Invalid URL structure or special characters detected.',
                'type'    => 'url'
            ]);
        }

        // Create controller name from request
        $controllerName = str_replace(' ', '', ucwords(str_replace('_', ' ', $request->getController())));
        $controllerClass = "Minic\\Controller\\$controllerName";

        // Check if controller does not exist, use send404
        if (!class_exists($controllerClass)) {
            return Response::sendError('404', [
                'message' => "Controller Not Found: $controllerClass",
                'type'    => 'controller'
            ]);
        }

        // Initialize controller and check action
        $controller = new $controllerClass();
        $baseAction = str_replace(' ', '', ucwords(str_replace('_', ' ', $request->getAction())));
        $methodSuffix = strtolower($request->getMethod());

        $possibleActions = [
            $baseAction,                    // index()
            "{$baseAction}__$methodSuffix",  // index__post()
            "{$baseAction}__{$methodSuffix}" // index__post()
        ];

        // Check each action
        foreach ($possibleActions as $action) {
            if (method_exists($controller, $action)) {
                return call_user_func_array([$controller, $action], $request->getParams());
            }
        }

        // If action does not exist, use send404
        return Response::sendError('404', [
            'message' => "Action Not Found: $controllerClass::{$baseAction}",
            'type'    => 'action'
        ]);
    }

    /**
     * Check URL validity (no special characters or invalid strings)
     *
     * @param string $url
     * @return bool
     */
    private static function isValidUrl(string $url): bool {
        // Check if the URL contains special characters, redundant slashes, or any abnormalities
        // Allows alphanumeric characters, underscores, hyphens, and dots
        return preg_match('/^[a-zA-Z0-9._-]+$/', $url);
    }
}
