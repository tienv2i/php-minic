<?php
namespace Minic\Core\Http;
use Minic\Core\Config;

class Response {
    /**
     * Send an HTTP response.
     *
     * @param string $content     The content to send.
     * @param int    $status      The HTTP status code.
     * @param string $contentType The Content-Type header.
     * @param array  $headers     Additional headers to send.
     */
    public static function send(string $content, int $status = 200, string $contentType = 'text/plain', array $headers = []) {
        http_response_code($status);
        header("Content-Type: $contentType");
        
        foreach ($headers as $key => $value) {
            header("$key: $value");
        }
        
        echo $content;
    }

    /**
     * Send a plain text response.
     *
     * @param string $content The text content.
     * @param int    $status  The HTTP status code.
     * @param array  $headers Additional headers.
     */
    public static function text(string $content, int $status = 200, array $headers = []) {
        self::send($content, $status, 'text/plain', $headers);
    }

    /**
     * Send an HTML response.
     *
     * @param string $content The HTML content.
     * @param int    $status  The HTTP status code.
     * @param array  $headers Additional headers.
     */
    public static function html(string $content, int $status = 200, array $headers = []) {
        self::send($content, $status, 'text/html', $headers);
    }

    /**
     * Send a JSON response.
     *
     * @param array $data    The data to encode as JSON.
     * @param int   $status  The HTTP status code.
     * @param array $headers Additional headers.
     */
    public static function json(array $data, int $status = 200, array $headers = []) {
        $headers['Content-Type'] = 'application/json';
        self::send(json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT), $status, 'application/json', $headers);
    }

    /**
     * Redirect to a different URL.
     *
     * @param string $url     The URL to redirect to.
     * @param int    $status  The HTTP status code.
     * @param array  $headers Additional headers.
     */
    public static function redirect(string $url, int $status = 302, array $headers = []) {
        http_response_code($status);
        header("Location: $url");
        
        foreach ($headers as $key => $value) {
            header("$key: $value");
        }
        
        exit;
    }

    /**
     * Render a Twig template and send the output as an HTML response.
     *
     * @param string $template The Twig template file name (with or without the .twig extension).
     * @param array  $data     Data to be passed to the template.
     * @param array  $headers  Additional headers.
     *
     * @throws \Exception If the Twig library is not available.
     */
    public static function renderTwig(string $template, array $data = [], array $headers = []) {
        // Determine the views directory from configuration or fallback to default
        $viewsDir = Config::get('views_dir', __DIR__ . '/../../views');
        
        // Ensure the template has the .twig extension
        if (substr($template, -10) !== '.twig.html') {
            $template .= '.twig.html';
        }
        
        // Initialize Twig loader and environment
        $loader = new \Twig\Loader\FilesystemLoader($viewsDir);
        $twig = new \Twig\Environment($loader, [
            // Uncomment and set a cache directory if needed
            // 'cache' => $viewsDir . '/cache',
            'debug' => true,
        ]);
        
        // Render the template with the provided data
        $content = $twig->render($template, $data);
        self::html($content, 200, $headers);
    }
}
