<?php
/**
 * Bootstrap.php - Core framework class
 * 
 * This file contains the main Bootstrap class for handling routing, views, and application execution.
 * 
 * @package App
 * @author Your Name
 * @version 1.0.0
 * @license MIT
 */

namespace App;
if (!defined('BASE_PATH')) die('Access denied!');

class Bootstrap {
    private static ?Bootstrap $instance = null;
    private array $config = ["hooks" => [], "view_defaults" => []], $path_segments = [], $routes = [];
    private string $path, $uri;

    /**
     * Constructor - Initializes request URI and path segments
     */
    public function __construct() {
        $uri = rtrim(parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH), '/');
        $script_name = $_SERVER["SCRIPT_NAME"];
        
        if (strpos($uri, $script_name) === 0) {
            $path = substr($uri, strlen($script_name));
        }
        else $path = $uri;
        
        $this->path = $path ?: '/';
        $this->path_segments = array_filter(explode('/', $this->path));
    }

    public static function getInstance () {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Sets up the framework with a given configuration
     * @param array $config
     * @return self
     */
    public static function setup(array $config = []): self {

        $instance = self::getInstance();
        $instance->config = array_merge($instance->config, $config);

        // Include helper files
        foreach ($instance->config['helpers'] as $helper) {
            $helperPath = rtrim($instance->config['helper_dir'] ?? '', '/') . '/' . ltrim($helper, '/').".php";
            if (file_exists($helperPath)) {
                include_once $helperPath;
            }
        }

        return $instance;
    }
    
    /**
     * Registers a route with a method, pattern, and callback
     */
    public function route(string $method, string $pattern, callable $callable): self {
        $pattern = preg_replace(
            ['/{slug}/', '/{word}/', '/{number}/', '/{any}/', '/{any\*}/', '/{file_name}/'], 
            ['([a-z0-9-]+)', '([a-zA-Z]+)', '(\d+)', '([^/]+)', '(.*)', '([\w\-.]+)'], 
            $pattern
        );
        $this->routes[] = [
            'method' => strtoupper($method),
            'pattern' => "~^$pattern$~",
            'callback' => $callable
        ];
        return $this;
    }
    
    /**
     * Registers GET routes
     */
    public function get(string $pattern, callable $callable): self {
        return $this->route('GET', $pattern, $callable);
    }
    /**
     * Registers POST routes
     */
    public function post(string $pattern, callable $callable): self {
        return $this->route('POST', $pattern, $callable);
    }
    /**
     * Dispatch function - matches requested URI to defined routes
     */
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
    
    /**
     * Triggers a registered event
     */
    public function triggerEvent(string $event) {
        if (isset($this->config['hooks'][$event]) && is_callable($this->config['hooks'][$event])) {
            call_user_func($this->config['hooks'][$event], $this);
        }
    }
    
    /**
     * Renders a view file with given data
     */
    public function render(string $view, array $data = []) {
        $this->triggerEvent('before_render');
        if (!isset($this->config['view_dir'])) {
            throw new \Exception("View directory is not set in config.");
        }
        $viewPath = rtrim($this->config['view_dir'], '/') . '/' . ltrim($view, '/');
        if (!file_exists($viewPath)) {
            throw new \Exception("View file not found: $viewPath");
        }
        $data = array_merge($this->config['view_defaults'], $data);
        extract($data);
        $app = $this; // Make $app available in views
        require $viewPath;
    }
    
    /**
     * Runs the application and dispatches routes
     */
    public function runApp() {
        $this->triggerEvent('before_dispatch');
        $this->dispatch();
    }
    
    /**
     * Logs a message to the output
     */
    public function log($message) {
        echo "<p><pre>" . htmlspecialchars($message) . "</pre></p>";
    }
    /**
     * Dumps a variable for debugging
     */
    public function dump($message) {
        echo "<p><pre>";
        var_dump($message);
        echo "</pre></p>";
    }
    /**
     * Dumps a private property if it exists
     */
    public function dump_private(string $name) {
        if (property_exists($this, $name)) {
            echo "<p><pre>";
            var_dump($this->$name);
            echo "</pre></p>";
        } else {
            echo "<p><pre>Property '$name' does not exist.</pre></p>";
        }
    }
    /**
     * Retrieves a configuration value
     */
    public function getConfig(string $name, $default = '') {
        return $this->config[$name] ?? $default;
    }
}
?>
