<?php
require_once DB;
require_once TRAITS . 'MakeInt.php';
require_once TRAITS . 'MakeString.php';


/** Users class */
class Users
{   
    // Объект класса DB
    private $DB = null;
    
    private $userId = null;
    // Имя таблицы в БД
    private static $table = 'users';
    
    
    use MakeInt;
    use MakeString;


    public function __construct($userId = null)
    {   
        $this->DB = DB::connect();
        //
        if(!is_null($userId) && (int) $userId > 0) {
            $this->userId = (int) $userId;
        }
    }


    /** Возвращает id пользователя
     * @return int
    */
    public function getUserId() {
        return $this->userId;
    }


    /** Возвращает пользователя или всех пользователей
     * @param array $props - пораметры фильтра
     * @return array
    */
    public function getUser($props = []) 
    {
        $query = 'SELECT * FROM ' . self::$table;
        $params = [];
        
        $flagAnd = false;
        
        if(!is_null($this->userId)) {
            // Проверка наличия WHERE
            if(!$flagAnd) {
                $query .= ' WHERE';
            } else {
                $query .= ' AND';
            }
            
            $query .= ' id = :id';
            $params[':id'] = $this->userId;
            
            $flagAnd = true;
        }
       
        $stmt = $this->DB->prepare($query);
        if(!$stmt->execute($params)) {
            return [];
        }
        
        $response = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $response;
    }


    /** Добавляет пользователя в таблицу 
     * @param array $props
     * @return int [1 - успех, 0 - ошибка]          
    */
    public function addUser($props = [])
    {   
        // Требуемые параметры
        $conditions = [
            'name' => null
        ];

        if(isset($props['name'])) {
            $conditions['name'] = $this->makeString($props['name']);
            if(is_null($conditions['name'])) {
                return 0;
            }
        } else {
            return 0;
        }

        if($this->isExistUser($conditions)) {
            return 0;
        }

        $query = 'INSERT INTO ' . self::$table;
        $params = [];

        $flagFirst = true;

        $keysLeft = ' (';
        $keysRight = ' VALUES (';
        foreach ($conditions as $key => $condition) {
            if(!is_null($condition)) {
                if(!$flagFirst) {
                    $keysLeft .= ', ';
                    $keysRight .= ', ';
                }
                
                $keysLeft .= $key;
                $keysRight .= ':' . $key;
                
                $params[':' . $key] = $condition;
                
                $flagFirst = false;
            }
        }

        $keysLeft .= ')';
        $keysRight .= ')';
        
        $query .= $keysLeft . $keysRight;

        $stmt = $this->DB->prepare($query);
        if(!$stmt->execute($params)) {
            return 0;
        }

        return 1;
    }


    /** Проверяет наличие записи(-ей) в БД
     * @param array $props
     * @return bool
    */
    public function isExistUser($props = [])
    {   

        if(empty($props)) {
            return 0;
        }
      
        $conditions = [
            'id' => null,
            'name' => null
        ];

        if(isset($props['id'])) {
            $conditions['id'] = $this->makeInt($props['id']);
        }
        if(isset($props['name'])) {
            $conditions['name'] = $this->makeString($props['name']);
        }
   
        $flag = true;
        foreach ($conditions as $value) {
            if(!is_null($value)) {
                $flag = false;
            }
        }
       
        if($flag) {
            return false;
        }

        $query = 'SELECT * FROM ' . self::$table;
        $params = [];

        $flagAnd = false;
        //
        foreach ($conditions as $key => $condition) {
            if(!is_null($condition)) {

                if(!$flagAnd) {
                    $query .= ' WHERE';
                } else {
                    $query .= ' AND';
                }
                //
                $query .= ' ' . $key . ' = :' . $key;
                $params[':' . $key] = $condition;

                $flagAnd = true;
            }
        }

        $stmt = $this->DB->prepare($query);
        if(!$stmt->execute($params)) {
            return false;
        }

        $response = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if(empty($response)) {
            return false;    
        }
        
        return true;
    }
}