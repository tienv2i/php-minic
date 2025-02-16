<?php
namespace Minic\Core;

/**
 * Abstract Singleton class to ensure only one instance of a subclass is created.
 */
abstract class Singleton {
    /**
     * Array of instances for each subclass.
     *
     * @var array<string, static>
     */
    private static array $instances = [];

    /**
     * Protected constructor to prevent creating a new instance from outside the class.
     *
     * @param mixed ...$args Optional arguments passed during instantiation.
     */
    protected function __construct(...$args) {
        // Initialize the instance with arguments if needed.
    }

    /**
     * Retrieve the single instance of the subclass.
     *
     * @param mixed ...$args Optional arguments to pass to the constructor.
     *
     * @return static The single instance of the called class.
     */
    public static function getInstance(...$args): static {
        $cls = static::class; // Late static binding: get the called class name.
        if (!isset(self::$instances[$cls])) {
            self::$instances[$cls] = new static(...$args);
        }
        return self::$instances[$cls];
    }

    /**
     * Private clone method to prevent cloning of the instance.
     */
    private function __clone() { }

    /**
     * Private unserialize method to prevent unserializing of the instance.
     */
    final function __wakeup() { }
}
