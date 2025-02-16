<?php

namespace Minic\Core;

/**
 * Application Bootstrap Class
 */
class Bootstrap extends Singleton {
    protected function __construct() {
        $this->loadHelpers();  // Load global helpers
        View::getInstance();   // Initialize View system (which loads Twig helpers)
    }

    /**
     * Run the application.
     */
    public function runApp() {
        Router::dispatch();
    }

    /**
     * Load global PHP helpers (not tied to Twig).
     */
    private function loadHelpers(): void {
        $helpersDir = Config::get('helpers_dir', dirname(__DIR__, 2) . '/src/helpers');

        if (!is_dir($helpersDir)) {
            return;
        }

        foreach (scandir($helpersDir) as $file) {
            if (pathinfo($file, PATHINFO_EXTENSION) === 'php') {
                require_once "$helpersDir/$file";
            }
        }
    }
}
