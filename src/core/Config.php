<?php
namespace Minic\Core;

/**
 * Config class for managing application settings.
 * Uses Singleton pattern and prevents re-initialization.
 */
class Config extends Singleton {
    /**
     * Configuration storage.
     *
     * @var array
     */
    private array $data = [];

    /**
     * Track whether the config has been initialized.
     *
     * @var bool
     */
    private bool $initialized = false;

    /**
     * Initialize the configuration once.
     *
     * @param array $config Initial configuration settings.
     *
     * @throws \Exception If already initialized.
     */
    public static function initialize(array $config = []): void {
        $instance = self::getInstance();
        if ($instance->initialized) {
            throw new \Exception("Config has already been initialized and cannot be reset.");
        }
        $instance->data = $config;
        $instance->initialized = true;
    }

    /**
     * Retrieve a configuration value.
     *
     * @param string $key     The configuration key.
     * @param mixed  $default Default value if key is not set.
     *
     * @return mixed The configuration value or default if not set.
     */
    public static function get(string $key, $default = "") {
        return self::getInstance()->data[$key] ?? $default;

    }

    /**
     * Set or update a configuration value.
     *
     * @param string $key   The configuration key.
     * @param mixed  $value The value to set.
     */
    public static function set(string $key, $value): void {
        $instance = self::getInstance();
        if (!$instance->initialized) {
            throw new \Exception("Config must be initialized before setting values.");
        }
        $instance->data[$key] = $value; // Allow overwriting existing values
    }

}
