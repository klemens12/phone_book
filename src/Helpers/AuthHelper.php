<?php

namespace App\Helpers;

use App\Helpers\SessionHelper;
use App\Models\UserModel;
use App\Controllers\Controller;

class AuthHelper {
    
    public static $controller;
    public static $session_timeout = 86400;
    
    public function __construct() 
    {
        self::$controller = new Controller();
    }
    
    /**
     * If the user has a valid password, authenticate them and set a session timeout.
     * 
     * @param string $password
     * @param \stdClass|null $user
     * @return void
     */
    public static function auth(string $password, ?\stdClass $user): bool
    {
        
        if ($user && password_verify($password, $user->password)) {
            
            $currentUser = SessionHelper::getArrayFromSession('current_user');
            
            if(empty($currentUser)){
                SessionHelper::addArrayToSession('current_user', 
                [
                    'user_id' => $user->id, 
                    'user_email' => $user->email,
                    'session_timeout' => time() + self::$session_timeout
                ]);  
            }
            
            return true;
        } else {
            return false;
        } 
    }
    
    /**
     * Check for user authentication and logout if the session has expired.
     * 
     * @return bool
     */
    public static function isAuth(): bool
    {
        
        $current_user = SessionHelper::getArrayFromSession('current_user');
          
        $res = false;
        if(!empty($current_user['user_id']) && !empty($current_user['user_email'])){
            $res = true;
        }
        if (isset($current_user['session_timeout']) && $current_user['session_timeout'] < time()) {
            self::logout();
            self::redirect('/');
        }
        
        return $res;
    }
    
    /**
     * Make a hash of the password string.
     * 
     * @param string $password
     * @return string
     */
    public static function hashPassw(string $password): string
    {
        
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        
        return $hashedPassword;
    }
    
    /**
     * Simple redirect.
     * 
     * @param string $url
     * @return void
     */
    public static function redirect(string $url): void
    {
        header('Location: '. $url);
    }
    
    /**
     * Destroy current session.
     * 
     * @return void
     */
    public static function logout(): void
    {
       SessionHelper::destroy();
    }
    
    /**
     * Load the current user from the session.
     * 
     * @return type
     */
    public static function getCurrentUser()
    {
        
        $currentUser = SessionHelper::getArrayFromSession('current_user');
        
        return $currentUser;
    }
}
