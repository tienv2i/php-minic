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


    /**
     * Send an error response based on error code.
     *
     * @param int    $errorCode The error code to respond with (e.g., 404, 500, etc.).
     * @param array  $data      Additional data to pass to the template.
     */
    public static function sendError(int $errorCode, array $data = []): void {
        // Set the response code to the provided error code
        http_response_code($errorCode);

        // Get the template name for the given error code from Config
        $template = Config::get("error_{$errorCode}_template", "{$errorCode}");

        // Render the error page using View::renderIfExists
        View::renderIfExists($template, $data, ['X-Status' => "$errorCode Error"]);
    }
   
}
