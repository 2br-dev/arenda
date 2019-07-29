<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/core/path.php';
require_once DB;
require_once CLASSES . 'Contracts.php';
require_once TRAITS . 'MakeInt.php';
require_once TRAITS . 'MakeString.php';
require_once TRAITS . 'MakeDate.php';
require_once TRAITS . 'MakeDouble.php';


/** Payments class */
class Payments
{   
    // Объект класса DB
    private $DB = null;
    // Объект класса Contract
    private $Contract = null;

    private $paymentId = null;
    // Имя таблицы в БД
    private static $table = 'payments';


    use MakeInt;
    use MakeString;
    use MakeDate;
    use MakeDouble;

    
    public function __construct($paymentId = null)
    {   
        $this->DB = DB::connect();
        
        if(!is_null($paymentId)) {
            $this->paymentId = $paymentId;
        }
        
        $this->Contracts = new Contracts();
    }


    /** Возвращает список выстовленных счетов 
     * @param int $userId
     * @param int $contractId
     * @param int $paymentId
     * @return array
    */
    public function getPayment($userId = null, $contractId = null, $paymentId = null) 
    {

        if(!is_null($userId)) {
            $userId = $this->makeInt($userId);
        }
        if(!is_null($contractId)) {
            $contractId = $this->makeInt($contractId);
        }
        if(!is_null($paymentId)) {
            $paymentId = $this->makeInt($paymentId);
        }

        $query = 'SELECT * FROM ' . self::$table;
        $params = [];
        
        $flagAnd = false;
        
        if(!is_null($contractId)) {
            if(!$flagAnd) {
                $query .= ' WHERE (';
            } else {
                $query .= ' AND (';
            }
            
            $query .= ' contract_id = :contract_id';
            $params[':contract_id'] = $contractId;
            
            $flagAnd = true;
        }
        
        if(!is_null($userId)) {
            $contracts = $this->Contracts->getContract($userId);
            if(!empty($contracts)) {
                if(!$flagAnd) {
                    $query .= ' WHERE (';
                } else if(!is_null($contractId)) {
                    $query .= ' OR ';
                } else {
                    $query .= ' AND (';
                }
                $flagAnd = true;
                //
                $counter = 1;
                foreach ($contracts as $row) {
                    if($counter > 1) {
                        $query .= ' OR';
                    }
                    $query .= ' contract_id = :contract_id' . $counter;
                    $params[':contract_id' . $counter] = (int) $row['id'];
                    
                    $counter++;
                }
                
                $query .= ')';
            } else if(!is_null($contractId)) {
                $query .= ')';
            }
        } else if(!is_null($contractId)) {
            $query .= ')';
        }
        
        if(!is_null($paymentId)) {
            if(!$flagAnd) {
                $query .= ' WHERE';
            } else {
                $query .= ' AND';
            }
            
            $query .= ' id = :id';
            $params[':id'] = $paymentId;
            
            $flagAnd = true;
        }
         
        $query .= ' ORDER BY date ASC';

        $stmt = $this->DB->prepare($query);
        if(!$stmt->execute($params)) {
            return [];
        }
 
        $response = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $response;
    }


    /** Добавляет новый счет
    * @param array $props
    * @return int [1 - успех, 0 - ошибка]
    */ 
    public function addPayment($props = []) 
    {
        $conditions = [
            'contract_id' => null,
            'date' => null,
            'payt' => null
        ];

        if(isset($props['contract_id'])) {
            $conditions['contract_id'] = $this->makeInt($props['contract_id']);
            if(is_null($conditions['contract_id'])) {
                return 0;
            }
        } else {
            return 0;
        }
        
        if(isset($props['date'])) {
            $conditions['date'] = $this->makeDate($props['date']);
            if(is_null($conditions['date'])) {
                return 0;
            }
        } else {
            return 0;
        }
        
        if(isset($props['payt'])) {
            $conditions['payt'] = $this->makeDouble($props['payt']);
            if(is_null($conditions['payt'])) {
                return 0;
            }
        } else {
            return 0;
        }

        if($this->isExistpayment($conditions)) {
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
    public function isExistPayment($props = [])
    {   

        if(empty($props)) {
            return 0;
        }

        $conditions = [
            'id' => null
        ];

        if(isset($props['id'])) {
            $conditions['id'] = $this->makeInt($props['id']);
            if(is_null($conditions['id'])) {
                return false;
            }
        } else {
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