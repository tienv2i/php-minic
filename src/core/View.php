<?php

namespace Minic\Core;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Twig\TwigFunction;

/**
 * View Singleton Class for managing Twig rendering.
 */
class View extends Singleton {
    protected static Environment $twig;
    protected static array $globalVars = [];
    protected static array $defaultData = []; // Store default data
    protected static array $twigFunctions = []; // Store functions before registering to Twig

    /**
     * Initialize the Twig environment with configurations.
     */
    protected function __construct() {
        $this->initializeTwig();
    }

    /**
     * Initialize Twig with configurations.
     */
    private function initializeTwig(): void {
        $viewsDir = Config::get('views_dir', dirname(__DIR__, 2) . '/views');

        $loader = new FilesystemLoader($viewsDir);
        self::$twig = new Environment($loader, [
            'cache' => Config::get('twig_cache', false) ? dirname(__DIR__, 2) . '/cache/twig' : false,
            'debug' => Config::get('twig_debug', false),
        ]);

        $this->setDefaultVariables();
        $this->registerTwigFunctions();
    }

    /**
     * Set default global variables for Twig templates.
     */
    private function setDefaultVariables(): void {
        self::$globalVars = [
            'base_url'   => Config::get('base_url', '/'),
            'app_name'   => Config::get('app_name', 'MyApp'),
            'csrf_token' => $_SESSION['csrf_token'] ?? '',
        ];

        foreach (self::$globalVars as $key => $value) {
            self::$twig->addGlobal($key, $value);
        }

        self::$defaultData = self::$globalVars;
    }

    /**
     * Set a global variable for templates.
     *
     * @param string|array $key
     * @param mixed|null $value
     */
    public static function setGlobal(string|array $key, mixed $value = null): void {
        if (is_array($key)) {
            foreach ($key as $k => $v) {
                self::setGlobal($k, $v);
            }
            return;
        }

        self::$globalVars[$key] = $value;
        if (isset(self::$twig)) {
            self::$twig->addGlobal($key, $value);
        }
    }

    /**
     * Set a default data variable (can be overridden in render()).
     *
     * @param string $key
     * @param mixed $value
     */
    public static function setData(string $key, mixed $value): void {
        self::$defaultData[$key] = $value;
    }

    /**
     * Register a new function to be used inside Twig templates.
     *
     * @param string|array $name
     * @param callable|null $function
     */
    public static function registerFunction(string|array $name, callable|null $function = null): void {
        if (is_array($name)) {
            foreach ($name as $funcName => $func) {
                self::registerFunction($funcName, $func);
            }
            return;
        }

        if ($function === null) {
            throw new \InvalidArgumentException("Function for '{$name}' cannot be null.");
        }

        if (isset(self::$twigFunctions[$name])) {
            return;
        }

        self::$twigFunctions[$name] = $function;

        if (isset(self::$twig)) {
            self::$twig->addFunction(new TwigFunction($name, $function));
        }
    }

    /**
     * Register all stored functions into Twig.
     */
    private function registerTwigFunctions(): void {
        foreach (self::$twigFunctions as $name => $function) {
            self::$twig->addFunction(new TwigFunction($name, $function));
        }
    }

    /**
     * Render a Twig template if it exists.
     *
     * @param string $template Template file (relative to the views directory).
     * @param array $data Data to pass to the template.
     * @param array $headers Response headers.
     * @throws \RuntimeException if the template does not exist.
     */
    public static function renderIfExists(string $template, array $data = [], array $headers = []): void {

        $twig_ext = Config::get('view_ext', '.twig');
        if (!str_ends_with($template, $twig_ext)) {
            $template .= $twig_ext;
        }
        // Check if the template exists
        if (self::$twig->getLoader()->exists($template)) {
            
            $finalData = array_merge(self::$defaultData, $data);
            $content = self::$twig->render($template, $finalData);
            Response::html($content, 200, $headers);
        } else {
            // Handle case where template does not exist (optional)
            // For now, we can return or throw an error, depending on your preference
            Response::html("Template '{$template}' not found.", 404);
            return;
        }

    }

    /**
     * Render a Twig template.
     *
     * @param string $template Template file (relative to the views directory).
     * @param array $data Data to pass to the template.
     * @param array $headers Response headers.
     * @throws \RuntimeException if the template does not exist.
     */
    public static function render(string $template, array $data = [], array $headers = []): void {

        $twig_ext = Config::get('view_ext', '.twig');
        if (!str_ends_with($template, $twig_ext)) {
            $template .= $twig_ext;
        }

        // Ensure the template exists
        if (!self::$twig->getLoader()->exists($template)) {
            throw new \RuntimeException("Template '{$template}' not found.");
        }

        $finalData = array_merge(self::$defaultData, $data);
        $content = self::$twig->render($template, $finalData);
        Response::html($content, 200, $headers);
    }

    /**
     * Get Twig instance (for debugging or manual manipulation).
     *
     * @return Environment
     */
    public static function getTwig(): Environment {
        return self::$twig;
    }

    /**
     * Reset all global variables, functions, and default data.
     */
    public static function reset(): void {
        self::$globalVars = [];
        self::$defaultData = [];
        self::$twigFunctions = [];
    }
}
