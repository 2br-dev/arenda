<?php
require_once DB;
require_once TRAITS . 'MakeInt.php';
require_once TRAITS . 'MakeString.php';
require_once TRAITS . 'MakeDouble.php';
require_once TRAITS . 'MakeDate.php';


/** Contracts class */
class Contracts
{   
    // Объект класса DB
    private $DB = null;
    //
    private $contractId = null;
    // Имя таблицы в БД
    private static $table = 'contracts';


    use MakeInt;
    use MakeString;
    use MakeDouble;
    use MakeDate;

    
    public function __construct($contractId = null)
    {   
        $this->DB = DB::connect();
        
        if(!is_null($contractId) && (int) $contractId > 0) {
            $this->contractId = (int) $contractId;
        }
    }


    /** Возвращает список договоров
     * @param array $props
     * @return array
    */
    public function getContract($userId = null) 
    {   
        if(!is_null($userId)) {
            $userId = $this->makeInt($userId);
        }

        $query = 'SELECT * FROM ' . self::$table;
        $params = [];

        $flagAnd = false;

        if(!is_null($userId)) {
            if(!$flagAnd) {
                $query .= ' WHERE';
            } else {
                $query .= ' AND';
            }
            //
            $query .= ' user_id = :user_id';
            $params[':user_id'] = $userId;
            //
            $flagAnd = true;
        }
        
        if(!is_null($this->contractId) && is_null($userId)) {
            if(!$flagAnd) {
                $query .= ' WHERE';
            } else {
                $query .= ' AND';
            }
            
            $query .= ' id = :id';
            $params[':id'] = $this->contractId;
            
            $flagAnd = true;
        }

        $stmt = $this->DB->prepare($query);
        if(!$stmt->execute($params)) {
            return [];
        }
        
        $response = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $response;
    }



    /** Добавляет договор в таблицу 
     * @param array $props - информация о пользователе
    */
    public function addContract($props = [])
    {
        $conditions = [
            'user_id' => null,
            'room_id' => null,
            'name' => null,
            'price' => null,
            'discount' => null,
            'discount_payment_zone' => 5, // по умолчанию
            'payment_zone' => 10, // по умолчанию
            'date_opening' => null,
            'date_closure' => null
        ];

        if(isset($props['user_id'])) {
            $conditions['user_id'] = $this->makeInt($props['user_id']);
            if(is_null($conditions['user_id'])) {
                return 0;
            }
        } else {
            return 0;
        }
        if(isset($props['room_id'])) {
            $conditions['room_id'] = $this->makeInt($props['room_id']);
            if(is_null($conditions['room_id'])) {
                return 0;
            }
        } else {
            return 0;
        }
        if(isset($props['name'])) {
            $conditions['name'] = $this->makeString($props['name']);
            if(is_null($conditions['name'])) {
                return 0;
            }
        } else {
            return 0;
        }
        
        if(isset($props['discount_payment_zone'])) {
            if((int) $props['discount_payment_zone'] !== 0) {
                $conditions['discount_payment_zone'] = $this->makeInt($props['discount_payment_zone']);
            } else {
                return 0;
            }
        } else {
            return 0;
        }
        
        if(isset($props['payment_zone'])) {
            if((int) $props['payment_zone'] !== 0) {
                $conditions['payment_zone'] = $this->makeInt($props['payment_zone']);
            } else {
                return 0;
            }
        } else {
            return 0;
        }
        // если зона дисконта превышает зону оплаты
        if($conditions['discount_payment_zone'] >= $conditions['payment_zone']) {
            return 0;
        } 
        
        if(isset($props['date_opening'])) {
            $conditions['date_opening'] = date('Y-m-d', strtotime($props['date_opening']));
        } else {
            return 0;
        }

        if(isset($props['date_closure'])) {
            $conditions['date_closure'] = date('Y-m-d', strtotime($props['date_closure']));
        } else {
            return 0;
        }

        if(isset($props['price'])) {
            $conditions['price'] = (double) $props['price'];
        } else {
            return 0;
        }

        if(isset($props['discount'])) {
            $conditions['discount'] = (double) $props['discount'];
        } else {
            return 0;
        }
                        
        if($this->isExistContract($conditions)) {
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
     * @param array $contractId
     * @return bool
    */
    public function isExistContract($props = [])
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
        
        foreach ($conditions as $key => $condition) {
            if(!is_null($condition)) {

                if(!$flagAnd) {
                    $query .= ' WHERE';
                } else {
                    $query .= ' AND';
                }
                
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