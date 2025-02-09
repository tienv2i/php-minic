<?php

class Minic
{
    protected static self $instance;
    protected array $routes = [];
    protected array $config = [];

    public static function setup(array $config = []): self
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }
        self::$instance->config = $config;
        return self::$instance;
    }

    public function get_config(string $name, mixed $default = null): mixed
    {
        return $this->config[$name] ?? $default;
    }

    public function route(string $method, string $pattern, callable $callback): self
    {
        $this->routes[] = [
            'method' => strtoupper($method),
            'pattern' => $pattern === '*' ? '@.*@' : $this->convertPattern($pattern),
            'callback' => $callback
        ];
        return $this;
    }

    public function get(string $pattern, callable $callback): self { return $this->route('GET', $pattern, $callback); }
    public function post(string $pattern, callable $callback): self { return $this->route('POST', $pattern, $callback); }
    public function put(string $pattern, callable $callback): self { return $this->route('PUT', $pattern, $callback); }
    public function delete(string $pattern, callable $callback): self { return $this->route('DELETE', $pattern, $callback); }
    public function patch(string $pattern, callable $callback): self { return $this->route('PATCH', $pattern, $callback); }
    public function options(string $pattern, callable $callback): self { return $this->route('OPTIONS', $pattern, $callback); }

    protected function convertPattern(string $pattern): string
    {
        return '@^' . preg_replace('/\{(\w+)\}/', '(?P<$1>[^/]+)', $pattern) . '$@';
    }

    protected function getCleanPath(): string
    {
        $scriptPath = $_SERVER['SCRIPT_NAME'];
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $cleanPath = preg_replace('@^' . preg_quote($scriptPath, '@') . '@', '', $path);
        return $cleanPath ?: '/';
    }

    public function dispatch(): void
    {
        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        $path = $this->getCleanPath();
        
        foreach ($this->routes as $route) {
            if (($route['method'] === $method || $route['method'] === '*') && preg_match($route['pattern'], $path, $matches)) {
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                call_user_func($route['callback'], $this, $params);
                return;
            }
        }
        
        $this->response_404();
    }

    public function render(string $template, array $params = [], array $options = []): void
    {
        $status = $options['status'] ?? 200;
        http_response_code($status);
        
        $templateDir = $this->config['template_dir'] ?? die("Template directory not set");
        $templatePath = $templateDir . DIRECTORY_SEPARATOR . $template . '.php';
        
        if (file_exists($templatePath)) {
            extract($params);
            include $templatePath;
        } else {
            echo "Template not found: $templatePath";
        }
    }

    public function response(string $body, array $options = []): void
    {
        $status = $options['status'] ?? 200;
        $headers = $options['headers'] ?? [];
        
        http_response_code($status);
        foreach ($headers as $key => $value) {
            header("$key: $value");
        }
        echo $body;
    }

    public function response_text(string $text, array $options = []): void
    {
        $options['headers']['Content-Type'] = 'text/plain; charset=UTF-8';
        $this->response($text, $options);
    }

    public function response_json(array $data, array $options = []): void
    {
        $options['headers']['Content-Type'] = 'application/json';
        $this->response(json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT), $options);
    }

    public function response_html(string $html, array $options = []): void
    {
        $options['headers']['Content-Type'] = 'text/html; charset=UTF-8';
        $this->response($html, $options);
    }

    public function response_404(string $message = "404 Not Found", array $options = []): void
    {
        $options['status'] = 404;
        $this->response_text($message, $options);
    }
}
