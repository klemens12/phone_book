<?php

namespace App\Models;
use App\Models\DbModel;
use App\Helpers\AuthHelper;

class PhoneBookModel {
    
    public static $table = 'phone_books';
    
    public function __construct() 
    {
        
        $this->db = DbModel::getConnection();
        $this->user = AuthHelper::getCurrentUser();
    }

    /**
     * Make a new phone book entry.
     * 
     * @param string $first_name
     * @param string $last_name
     * @param string $phone
     * @param string $email
     * @return bool|array
     */
    public function create(string $first_name, string $last_name, string $phone, string $email): bool|array
    {
        
        $data = array(
            'user_id' => $this->user['user_id'],
            'user_fname' => $first_name,
            'user_lname' => $last_name,
            'user_phone' => $phone,
            'user_email' => $email,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        );
        $res = $this->db->insert(self::$table, $data);

        return $res;
    }
    
    /**
     * Update book entry field by id.
     * 
     * @param string $field
     * @param mixed $value
     * @param int $id
     * @return bool
     */
    public function updateById(string $field, $value, int $id)
    {
        
        $data = array(
            $field => $value,
            'updated_at' => date('Y-m-d H:i:s')
        );
        
        return $this->db->update(self::$table, $data, 'id = :id', [
            ':id' => $id
        ]);
    }
    
    /**
     * Delete phone book entry by id.
     * 
     * @param int $id
     * @return type
     */
    public function delete(int $id)
    {
        
        return $this->db->delete(self::$table, 'id = :id', [
            ':id' => $id
        ]);
    }
}