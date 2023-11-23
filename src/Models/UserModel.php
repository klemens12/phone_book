<?php

namespace App\Models;
use App\Models\DbModel;
use App\Helpers\AuthHelper;

class UserModel {

    public static $table = 'users';
    
    public function __construct() 
    {

        $this->db = DbModel::getConnection();
    }

    /**
     * Load user object by email.
     * 
     * @param string $email
     * @return null|stdClass
     */
    public function getUserByEmail(string $email)
    {
        
        $res = $this->db->select(self::$table, 'email = :email', [
            ':email' => $email
        ]);
      
        if(!empty($res)) return $res[0];
    }
    
    /**
     * Load phone book entries by the user.
     * 
     * @return null|stdClass
     */
    public function getPhoneBook()
    {
        
        $user = AuthHelper::getCurrentUser();
        $results = $this->db->join($user, 'pbByUser');
        
        return $results;
    }

    /**
     * Check the user existence by login and email.
     * 
     * @param string $login
     * @param string $email
     * @return boolean
     */
    public function verifyUserExistence(string $login, string $email): bool
    {
        
        $user = $this->db->select(self::$table, 'email = :email, login = :login', [
            ':login' => $login,
            ':email' => $email
        ], 'OR');
        
        $res = true;
        if(empty($user)) $res = false;
        
        return $res;
    }

    /**
     * Insert new user
     * 
     * @param string $login
     * @param string $email
     * @param string $hashedPassword
     * @return bool|array
     */
    public function create(string $login, string $email, string $hashedPassword): bool|array
    {
        
        $data = array(
            'login' => $login,
            'email' => $email,
            'password' => $hashedPassword,
            'created_at' => date('Y-m-d H:i:s')
        );
        
        return $this->db->insert(self::$table, $data);
    }
}
