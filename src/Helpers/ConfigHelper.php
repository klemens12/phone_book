<?php
namespace App\Helpers;

class ConfigHelper {

    private static $config;

    public static function load($configFile) 
    {
        
        if (file_exists($configFile)) {
            self::$config = require $configFile;
        } else {
            throw new \Exception("Config file not found");
        }
    }

    /**
     * Load an array of configurations.
     * 
     * @return self
     */
    public static function all() 
    {
        return self::$config;
    }

}
