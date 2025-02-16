<?php
namespace Minic\Core;

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
   
}
