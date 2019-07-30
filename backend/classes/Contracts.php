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
    public function getContract($props = []) 
    {   
        $query = 'SELECT * FROM ' . self::$table;
        $params = [];

        $flagAnd = false;
        
        if(!is_null($this->contractId)) {
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
            'name' => null,
            'discount_payment_zone' => 5, // по умолчанию
            'payment_zone' => 10 // по умолчанию
        ];
        $conditions['date_opening'] = date('Y-m-d'); // по умолчанию
        $conditions['date_closure'] = date('Y-m-t', strtotime('+12 months', strtotime($conditions['date_opening']))); // по умолчанию на 12 месяцев

        if(isset($props['user_id'])) {
            $conditions['user_id'] = $this->makeInt($props['user_id']);
            if(is_null($conditions['user_id'])) {
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
        
        if(isset($props['length'])) {
            if(strlen($props['length'])) {
                $conditions['length'] = $this->makeInt($props['length']);
            }
        }
        
        if(isset($props['discount_payment_zone'])) {
            if((int) $props['discount_payment_zone'] !== 0) {
                $conditions['discount_payment_zone'] = $this->makeInt($props['discount_payment_zone']);
            }
        }
        
        if(isset($props['payment_zone'])) {
            if((int) $props['payment_zone'] !== 0) {
                $conditions['payment_zone'] = $this->makeInt($props['payment_zone']);
            }
        }
        // если зона дисконта превышает зону оплаты
        if($conditions['discount_payment_zone'] >= $conditions['payment_zone']) {
            return 0;
        }
        
        if(isset($props['date_opening'])) {
            $conditions['date_opening'] = $this->makeDate($props['date_opening']);
            if(is_null($conditions['date_opening'])) {
               return 0; 
            }
            $conditions['date_closure'] = date('Y-m-t', strtotime('+12 months', strtotime($conditions['date_opening']))); // по умолчанию на 12 месяцев
        }
        
        if(isset($props['date_closure'])) {
            $conditions['date_closure'] = $this->makeDate($props['date_closure']);
            if(is_null($conditions['date_closure'])) {
               return 0; 
            }
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


    /** Устанавливает стоимость за расчетный период 
     * @param double $price
     * @return int
    */
    public function setPrice($price)
    {
        if(is_null($this->contractId)) {
            return 0;
        }
        
        if(!is_null($price)) {
            $price = $this->makeDouble($price);
            if(is_null($price)) {
                return 0;
            }
        } else {
            return 0;
        } 
         
        if(!$this->isExistContract(['id' => $this->contractId])) {
            return 0;
        }
        
        $query = 'UPDATE ' . self::$table . ' SET price = :price WHERE id = :id';
        $params = [
            ':id' => $this->contractId,
            ':price' => $price,
        ];
        $stmt = $this->DB->prepare($query);
        if(!$stmt->execute($params)) {
            return 0;
        } 

        return 1;
    }


    /** Устанавливает скидку на цену за расчетный период 
     * @param double $discount
     * @return int 
    */
    public function setDiscount($discount)
    {
        if(is_null($this->contractId)) {
            return 0;
        }
        
        if(!is_null($discount)) {
            $discount = $this->makeDouble($discount);
            if(is_null($discount)) {
                return 0;
            }
        } else {
            return 0;
        }
        
        if(!$this->isExistContract(['id' => $this->contractId])) {
            return 0;
        }
        
        $query = 'UPDATE ' . self::$table . ' SET discount = :discount WHERE id = :id';
        $params = [
            ':id' => $this->contractId,
            ':discount' => $discount,
        ];
        $stmt = $this->DB->prepare($query);
        if(!$stmt->execute($params)) {
            return 0;
        } 

        return 1;
    }


    /** Возвращает расчитанную стоимость за расчетный период с учетом скидки
     * @return double
    */
    public function getCost() 
    {
        if(is_null($this->contractId)) {
            return null;
        }
        
        if(!$this->isExistContract(['id' => $this->contractId])) {
            return null;
        }
        
        $query = 'SELECT price, discount FROM ' . self::$table . ' WHERE id = :id';
        $params = [
            ':id' => $this->contractId
        ];
        
        $stmt = $this->DB->prepare($query);
        if(!$stmt->execute($params)) {
            return null;
        } 
        $response = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $response = array_shift($response);
        
        $price = (double) $response['price'];
        $discount = (double) $response['discount'];
        
        return $price - $price * $discount / 100;
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