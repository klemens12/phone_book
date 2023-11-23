<?php

namespace App\Models;
use App\Helpers\ConfigHelper;
use App\Models\UserModel;
use App\Models\PhoneBookModel;

class DbModel {
    
    private \PDO $_conn;
    private static ?self $_instance = null;
    private array $_dbConfig;
    private $_dbHost;
    private $_dbName;
    private $_dbUsername;
    private $_dbPassword;
    
    /**
     * 
     * @throws \PDOException
     */
    protected function __construct() 
    {
        
        ConfigHelper::load(__DIR__ . '/../../config/config.php');
        $config = ConfigHelper::all();
       
        $this->_dbConfig = $config['database'];
        $this->_dbHost = $this->_dbConfig['db_host'];
        $this->_dbName = $this->_dbConfig['db_name'];
        $this->_dbUsername = $this->_dbConfig['db_username'];
        $this->_dbPassword = $this->_dbConfig['db_password'];

        try{
            $this->_conn = new \PDO("mysql:host=" . $this->_dbHost . ";dbname=" . $this->_dbName, $this->_dbUsername, $this->_dbPassword);
            $this->_conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        }  catch (\PDOException $e) {
            throw new \PDOException ("Connection failed: " . $e->getMessage());
        }
        
      
    }
    
    /**
     * Join data from db.
     * 
     * @param array $user
     * @param string $type
     * @return object|array
     */
    public function join(array $user, string $type)
    {
        
        switch($type){
            case 'pbByUser':
                
                $user_id = $user['user_id']; // Assuming you have the user's ID

                $query = "SELECT " . PhoneBookModel::$table . ".*
                    FROM " . UserModel::$table . "
                    LEFT JOIN " . PhoneBookModel::$table . " ON users.id = " . PhoneBookModel::$table . ".user_id
                    WHERE " . UserModel::$table . ".id = :user_id ORDER BY `id` DESC";

                $stmt = $this->_conn->prepare($query);
                $stmt->bindParam(':user_id', $user_id, \PDO::PARAM_INT); // Assuming user ID is an integer
                $stmt->execute();

            
            $results = $stmt->fetchAll(\PDO::FETCH_OBJ);
            if (empty($results[0]->id)) {
                $results = [];
            }
           
            return $results;
        }
    }
 
    /**
     * Get PDO connection instance.
     * 
     * @return self
     */
    public static function getConnection(): self 
    {
        return self::$_instance ??= new self();
    }

    /**
     * Insert data to db
     * 
     * @param string $table
     * @param array $data
     * @return array|boolean
     */
    public function insert($table, $data) 
    {
        
        $columns = implode(",", array_keys($data));
        $values = ":" . implode(", :", array_keys($data));
        $sql = "INSERT INTO $table ($columns) VALUES ($values)";
        
        try {
            $stmt = $this->_conn->prepare($sql);
            foreach ($data as $key => $value) {
                $stmt->bindValue(':' . $key, $value);
            }
            $result = $stmt->execute();
            
          
            if ($result) {
                $insert_id = $this->_conn->lastInsertId();
                
                return ['res' => $result, 'insert_id' => $insert_id];
            } else {
                return ['res' => $result, 'insert_id' => null];
            }
        } catch(\PDOException $e){
            return false;
        }
      
    }

    /**
     * 
     * @param string $table
     * @param array $data
     * @param string|null $where
     * @param array $params
     * @param string|null $type
     * @return boolean
     */
    public function update($table, $data, $where = null, $params = [], $type = null) 
    {
        
        if ($type === 'AND' || is_null($type)) {
            $implodeRule = ' AND ';
        } 
        if ($type === 'OR') {
            $implodeRule = ' OR ';
        }
        
        $sql = "UPDATE $table SET ";
        foreach ($data as $key => $value) {
            $sql .= "$key = :$key,";
        }
        $sql = rtrim($sql, ',');
       
        if ($where != null) {
            $sql .= " WHERE ";

            $paramsFinal = [];
            $conditions = [];
            $pairs = explode(',', $where);

            foreach ($pairs as $pair) {
                $pairParts = explode('=', trim($pair));

                $column = $pairParts[0];
                $param = ":$column";
                $value = $params[trim($param)];

                $conditions[] = trim("$column = " . trim($param));
                $paramsFinal[trim($param)] = $value;
            }
 
            $sql .= implode($implodeRule, $conditions);
        }
        
        try {
            $stmt = $this->_conn->prepare($sql);
            
            foreach ($data as $key => $value) {
                $stmt->bindValue(':' . $key, $value);
            }
            foreach ($paramsFinal as $param => $value) {
                $stmt->bindValue($param, $value, \PDO::PARAM_STR);
            }
            $result = $stmt->execute();
            
            return $result ? true : false;
        } catch(\PDOException $e){
            return false;
        }     
    }

    /**
     * Select data from DB with ?$where, params and ?$type
     * 
     * @param string $table
     * @param  string|null $where
     * @param array $params
     * @param string|null $type
     * @return \stdClass
     */
    public function select($table, $where = null, $params = [], $type = null) 
    {
        
        if ($type === 'AND' || is_null($type)) {
            $implodeRule = ' AND ';
        } 
        if ($type === 'OR') {
            $implodeRule = ' OR ';
        }
        
        $sql = "SELECT * FROM $table";
        if ($where != null) {
            $sql .= " WHERE ";

            $paramsFinal = [];
            $conditions = [];
            $pairs = explode(',', $where);

            foreach ($pairs as $pair) {
                $pairParts = explode('=', trim($pair));

                $column = $pairParts[0];
                $param = ":$column";
                $value = $params[trim($param)];

                $conditions[] = trim("$column = " . trim($param));
                $paramsFinal[trim($param)] = $value;
            }
 
            $sql .= implode($implodeRule, $conditions);
        }

        $stmt = $this->_conn->prepare($sql);
        
        foreach ($paramsFinal as $param => $value) {
            $stmt->bindValue($param, $value, \PDO::PARAM_STR);
        }
        
        $stmt->execute();
    
        return $stmt->fetchAll(\PDO::FETCH_OBJ);
    }

    /**
     * Delete record from DB with $where, params and ?$type 
     * 
     * @param string $table
     * @param string $where
     * @param array $params
     * @param string|null $type
     * @return boolean|array
     */
    public function delete($table, $where, $params = [], $type = null) 
    {
        
        if ($type === 'AND' || is_null($type)) {
            $implodeRule = ' AND ';
        } 
        if ($type === 'OR') {
            $implodeRule = ' OR ';
        }
        
        $sql = "DELETE FROM $table WHERE ";
        
        $paramsFinal = [];
        $conditions = [];
        $pairs = explode(',', $where);

        foreach ($pairs as $pair) {
            $pairParts = explode('=', trim($pair));

            $column = $pairParts[0];
            $param = ":$column";
            $value = $params[trim($param)];

            $conditions[] = trim("$column = " . trim($param));
            $paramsFinal[trim($param)] = $value;
        }
 
        $sql .= implode($implodeRule, $conditions);
           
        try{
            $stmt = $this->_conn->prepare($sql);
           
            foreach ($paramsFinal as $param => $value) {
                $stmt->bindValue($param, $value, \PDO::PARAM_STR);
            }
            
            $result = $stmt->execute();
            return ['res' => $result];
        } catch (Exception $ex) {
            return false;
        }
    }

    /**
     * Clone cancel.
     * 
     * @return boolean
     */
    public function __clone()
    {
        return false;
    }
    
    /**
     * Wakeup cancel.
     * 
     * @return boolean
     */
    public function __wakeup()
    {
        return false;
    }
}

