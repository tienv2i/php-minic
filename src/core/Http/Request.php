<?php
namespace Minic\Core\Http;

use Minic\Core\Singleton;

class Request extends Singleton {
    private string $uri;
    private string $method;
    private string $controller, $action;
    private array $params;
    
    protected function __construct() {
        $this->uri = $this->getSanitizedUri();
        $this->method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        $this->parseUri();
    }
    
    private function getSanitizedUri(): string {

        $uri = rtrim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH),'/');
        $script_name = $_SERVER['SCRIPT_NAME'];

        if (strpos($uri, $script_name) === 0) {
            $path = substr($uri, strlen($script_name));
        } else {
            $path = $uri;
        }

        return $this->sanitizePath($path ?: '/');
    }
    
    private function sanitizePath(string $path): string {
        return preg_replace('/[^a-zA-Z0-9\/\-_]/', '', $path);
    }

    private function parseUri() {
        $segments = array_filter(explode('/', trim($this->uri, '/')));
    
        $this->controller = !empty($segments) ? ucfirst(array_shift($segments)) : 'Home';
        $this->action = !empty($segments) ? array_shift($segments) : 'index';
        $this->params = $segments;
    }

    public function getUri(): string {
        return $this->uri;
    }
    
    public function getMethod(): string {
        return $this->method;
    }
    
    public function getController(): string {
        return $this->controller;
    }
    
    public function getAction(): string {
        return $this->action;
    }
    
    public function getParams(): array {
        return $this->params;
    }
}