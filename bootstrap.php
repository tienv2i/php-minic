<?php
namespace App;

class Bootstrap {
    private static ?Bootstrap $instance = null;
    private array $config = ["hooks" => []], /* $path_segments = [], */ $routes = [];
    private string $path, $uri;

    public function __construct() {

        $uri = rtrim(parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH), '/');
        $script_name = $_SERVER["SCRIPT_NAME"];
        
        if (strpos($uri, $script_name) === 0) {
            $path = substr($uri, strlen($script_name));
        }
        else $path = $uri;
        
        $this->path = $path ?: '/';
        // $this->path_segments = array_filter(explode('/', $this->path));
    }
    
    public static function setup(array $config = []): self {
        if (self::$instance === null) {
            self::$instance = new self();
            
        }
        self::$instance->config = array_merge(self::$instance->config, $config);
        return self::$instance;
    }
    
    public function route(string $method, string $pattern, callable $callable): self {
        $pattern = preg_replace(['/{slug}/', '/{word}/', '/{number}/', '/{any}/', '/{any\*}/'], 
                                ['([a-z0-9-]+)', '([a-zA-Z]+)', '(\d+)', '([^/]+)', '(.*)'], $pattern);
        $this->routes[] = [
            'method' => strtoupper($method),
            'pattern' => "~^$pattern$~",
            'callback' => $callable
        ];
        return $this;
    }
    
    public function get(string $pattern, callable $callable): self {
        return $this->route('GET', $pattern, $callable);
    }
    public function post(string $pattern, callable $callable): self {
        return $this->route('POST', $pattern, $callable);
    }
    public function put(string $pattern, callable $callable): self {
        return $this->route('PUT', $pattern, $callable);
    }
    public function delete(string $pattern, callable $callable): self {
        return $this->route('DELETE', $pattern, $callable);
    }
    
    public function dispatch() {
        $method = $_SERVER['REQUEST_METHOD'];
        foreach ($this->routes as $route) {
            if ($route['method'] === $method && preg_match($route['pattern'], $this->path, $matches)) {
                array_shift($matches);
                return call_user_func($route['callback'], $this, $matches);
            }
        }
        http_response_code(404);
        echo "404 Not Found";
    }
    
    public function triggerEvent(string $event) {
        if (isset($this->config['hooks'][$event]) && is_callable($this->config['hooks'][$event])) {
            call_user_func($this->config['hooks'][$event], $this);
        }
    }
    
    public function response($content, int $status = 200, string $contentType = 'text/html') {
        http_response_code($status);
        header("Content-Type: $contentType");
        echo $content;
    }
    
    public function responseText(string $text, int $status = 200) {
        $this->response($text, $status, 'text/plain');
    }
    
    public function responseJson(array $data, int $status = 200) {
        $this->response(json_encode($data), $status, 'application/json');
    }
    
    public function render(string $view, array $data = []) {

        $this->triggerEvent('before_render');

        if (!isset($this->config['view_dir'])) {
            throw new \Exception("View directory is not set in config.");
        }
        $viewPath = rtrim($this->config['view_dir'], '/') . '/' . ltrim($view, '/');
        if (!file_exists($viewPath)) {
            throw new \Exception("View file not found: $viewPath");
        }
        extract($data);
        require $viewPath;
    }
    
    public function runApp() {
       
        // Trigger before dispatch event
        $this->triggerEvent('before_dispatch');
        
        $this->dispatch();
    }
    
    public function log($message) {
        echo "<p><pre>" . htmlspecialchars($message) . "</pre></p>";
    }
    public function dump($message) {
        echo "<p><pre>";
        var_dump($message);
        echo "</pre></p>";
    }
    public function dump_private(string $name) {
        if (property_exists($this, $name)) {
            echo "<p><pre>";
            var_dump($this->$name);
            echo "</pre></p>";
        } else {
            echo "<p><pre>Property '$name' does not exist.</pre></p>";
        }
    }
    public function get_config(string $name, $default = '') {
        return $this->config[$name] ?? $default;
    }
}
