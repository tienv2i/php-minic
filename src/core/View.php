<?php

namespace Minic\Core;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Twig\TwigFunction;

/**
 * View Singleton Class for managing Twig rendering.
 */
class View extends Singleton {
    private Environment $twig;
    private array $globalVars = [];
    private array $defaultData = []; // Store default data

    protected function __construct() {
        $this->initializeTwig();
    }

    /**
     * Initialize Twig with configurations.
     */
    private function initializeTwig(): void {
        $viewsDir = Config::get('views_dir', dirname(__DIR__, 2) . '/views');

        $loader = new FilesystemLoader($viewsDir);
        $this->twig = new Environment($loader, [
            'cache' => Config::get('twig_cache', false) ? dirname(__DIR__, 2) . '/cache/twig' : false,
            'debug' => Config::get('twig_debug', false),
        ]);

        $this->setDefaultVariables();
        $this->setTwigHelpers();
    }

    /**
     * Set default global variables for Twig templates.
     */
    private function setDefaultVariables(): void {
        $this->globalVars = [
            'base_url'   => Config::get('base_url', '/'),
            'app_name'   => Config::get('app_name', 'MyApp'),
            'csrf_token' => $_SESSION['csrf_token'] ?? '',
        ];

        foreach ($this->globalVars as $key => $value) {
            $this->twig->addGlobal($key, $value);
        }

        // Set global variables as default data
        $this->defaultData = $this->globalVars;
    }

    /**
     * Register Twig-specific helpers.
     */
    private function setTwigHelpers(): void {
        $twigHelpers = Config::get('twig_helpers', []);
        
        foreach ($twigHelpers as $name => $function) {

            if (function_exists($function)) {
                $this->twig->addFunction(new TwigFunction($name, $function));
            }
        }
    }

    /**
     * Set a default data variable (can be overridden in render()).
     * 
     * @param string $key
     * @param mixed $value
     */
    public static function setData(string $key, mixed $value): void {
        self::getInstance()->defaultData[$key] = $value;
    }

    /**
     * Render a Twig template.
     *
     * @param string $template Template file (relative to views directory).
     * @param array $data Data to pass to the template.
     */
    public static function render(string $template, array $data = [], array $headers = []): void {
        $twig_ext = Config::get('view_ext', '.twig');
        if (strpos($template, $twig_ext, -strlen($twig_ext)) !== 0) {
            $template .= $twig_ext;
        }

        // Merge default data with provided data
        $finalData = array_merge(self::getInstance()->defaultData, $data);

        $content = self::getInstance()->twig->render($template, $finalData);
        Response::html($content, 200, $headers);
    }

    /**
     * Get Twig instance (for debugging or manual manipulation).
     *
     * @return Environment
     */
    public static function getTwig(): Environment {
        return self::getInstance()->twig;
    }
}
