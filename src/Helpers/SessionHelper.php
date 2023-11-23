<?php
namespace App\Helpers;

class SessionHelper {

    /**
     * Start new session if not started.
     * 
     * @return void
     */
    public static function start(): void
    {
        
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Destroy the session.
     * 
     * @return void
     */
    public static function destroy(): void
    {
        
        if (session_status() == PHP_SESSION_ACTIVE) {
            session_destroy();
        }
    }

    /**
     * Add named array to session.
     * 
     * @param string $key
     * @param array $value
     * @return void
     */
    public static function addArrayToSession(string $key, array $value): void
    {
        $_SESSION[$key] = $value;
    }

    /**
     * Load an named array from session.
     * 
     * @param string $key
     * @return array|null
     */
    public static function getArrayFromSession(string $key): ?array
    {
        return $_SESSION[$key] ?? null;
    }

    /**
     * Add item to session.
     * 
     * @param string $key
     * @param type $value
     * @return void
     */
    public static function addItemToSession(string $key, $value): void
    {
        $_SESSION[$key] = $value;
    }

    /**
     * Load item from session.
     * 
     * @param string $key
     * @return type
     */
    public static function getItemFromSession(string $key)
    {
        return $_SESSION[$key] ?? null;
    }

    /**
     * Remove named array in session.
     * 
     * @param string $key
     * @return void
     */
    public static function removeArrayFromSession(string $key): void
    {
        unset($_SESSION[$key]);
    }

    /**
     * Remove item in session.
     * 
     * @param string $key
     * @return void
     */
    public static function removeItemFromSession(string $key): void
    {
        unset($_SESSION[$key]);
    }
    
    /**
     * Generate new csrf token for sequrity.
     * 
     * @return string
     */
    private static function getCSRFString(): string 
    {
        return bin2hex(random_bytes(32));
    }
    
    /**
     * Check and generate csrf token in need it and store to session.
     * 
     * @return string
     */
    public static function generateCSRFToken() 
    {
        if (empty(self::getItemFromSession('csrf_token'))) {
            self::addItemToSession('csrf_token', self::getCSRFString());
        }
    
        return self::getItemFromSession('csrf_token');
    }
    
    /**
     * Update csrf token in current session.
     * 
     * @return void
     */
    public static function refreshCSRFToken(): void
    {
        self::addItemToSession('csrf_token', self::getCSRFString());
    }

}
