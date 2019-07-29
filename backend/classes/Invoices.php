<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/core/path.php';
require_once DB;
require_once CLASSES . 'Contracts.php';
require_once TRAITS . 'MakeInt.php';
require_once TRAITS . 'MakeString.php';
require_once TRAITS . 'MakeDate.php';
require_once TRAITS . 'MakeDouble.php';
require_once TRAITS . 'MakeUnix.php';


/** Invoices class */
class Invoices
{   
    // Объект класса DB
    private $DB = null;
    // Объект класса Contract
    private $Contract = null;
    
    private $invoiceId = null;
    // Имя таблицы в БД
    private static $table = 'invoices';


    use MakeInt;
    use MakeString;
    use MakeDate;
    use MakeDouble;
    use MakeUnix;

    
    public function __construct($invoiceId = null)
    {   
        $this->DB = DB::connect();
        //
        if(!is_null($invoiceId)) {
            $this->invoiceId = $invoiceId;
        }
        //
        $this->Contracts = new Contracts();
    }


    /** Возвращает список выстовленных счетов 
     * @param int $userId
     * @param int $contractId
     * @param int $invoiceId
     * @return array
    */
    public function getinvoice($userId = null, $contractId = null, $invoiceId = null)
    {
        if(!is_null($userId)) {
            $userId = $this->makeInt($userId);
        }
        if(!is_null($contractId)) {
            $contractId = $this->makeInt($contractId);
        }
        if(!is_null($invoiceId)) {
            $invoiceId = $this->makeInt($invoiceId);
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
        
        if(!is_null($invoiceId)) {
            if(!$flagAnd) {
                $query .= ' WHERE';
            } else {
                $query .= ' AND';
            }
            //
            $query .= ' id = :id';
            $params[':id'] = $invoiceId;
            //
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
    * test: 1563972016
    */ 
    public function addinvoice($props = []) 
    {
        
        $conditions = [ // Требуемые параметры
            'contract_id' => null,
            'date' => null,
            'amount' => null, // по умолчанию
            'specific_amount' => null, // по умолчанию
        ];

        if(isset($props['contract_id'])) {
            $conditions['contract_id'] = $this->makeInt($props['contract_id']);
            if(is_null($conditions['contract_id'])) {
                return 0;
            }
        } else {
            return 0;
        }
        
        if(isset($props['year'])) {
            if(is_null($this->makeInt($props['year']))) {
                return 0;
            }
        } else {
            return 0;
        }

        if(isset($props['month'])) {
            if(is_null($this->makeInt($props['month']))) {
                return 0;
            }
        } else {
            return 0;
        }

        $conditions['date'] = $this->createDate($props['year'], $props['month']);
        if(is_null($conditions['date'])) {
            return 0;
        }
        
        if(isset($props['amount'])) {
            $conditions['amount'] = $this->makeDouble($props['amount']);
            if(is_null($conditions['amount']) || $conditions['amount'] == 0) {
                $conditions['amount'] = null;
                $conditions['specific_amount'] = null;
            }
        }

        $thisContract = array_shift( $this->Contracts->getContract(null, $conditions['contract_id']) );
        $dateOpening =  $this->makeUnix($thisContract['date_opening']);
        $dateThisInvoice = $this->makeUnix($conditions['date']);
        
        if($this->isNotFullMonth($dateOpening, $dateThisInvoice)) {
            $conditions['amount'] = $this->getPartialAmount((double) $thisContract['price'], $dateOpening);
            $conditions['specific_amount'] = 1;
        }
        
        if(is_null($conditions['specific_amount'])) {
            $price = (double) $thisContract['price'];
            
            if(is_null($price)) {
                return 0;
            }
            
            $conditions['amount'] = $price;
        }
        

        if($this->isExistinvoice($conditions)) {
            return 0;
        }

        $query = 'INSERT INTO ' . self::$table;
        $params = [];

        $flagFirst = true; // Условие для построения запроса 
        
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
    public function isExistinvoice($props = [])
    {   
        if(empty($props)) { // Если не переданы параметры
            return 0;
        }
        
        $conditions = [ // Требуемые параметры
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
                if(!$flagAnd) { // Проверка наличия WHERE
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


    private function createDate($year = null, $month = null)
    {
        $year = $this->makeInt($year);
        $month = $this->makeInt($month);

        if(is_null($year) || $year > 2050 || $year < 2010) {
            return null;
        }

        if(is_null($month) || $month > 12 || $year < 1) {
            return null;
        }

        return date('Y-m-d', strtotime($year.'-'.$month.'-01'));
    }


     /** Возвращает стоимость за часть месяца 
     * @param double $amount
     * @param int $date
     * @return double
    */
    private function getPartialAmount($amount = null, $date = null)
    {
        if(is_null($amount) || is_null($date)) {
            return null;
        }
        
        $dayStart = date('d', $date);
        $countDays = date('t', $date);
        $remainsDays = $countDays - $dayStart + 1;

        return ceil($amount / $countDays * $remainsDays); // округляет в большую сторону
    }


    /** Проверяет что счет выставляется на часть месяца 
     * @param int $dateOpening
     * @param int $dateInvoice
     * @return bool
    */
    private function isNotFullMonth($dateOpening = null, $dateInvoice = null) 
    {   
        if(is_null($dateOpening) || is_null($dateInvoice)) {
            return null;
        }
        
        $invoiceMonthLength = (int) date('t', $dateOpening);
        $monthToSeconds = $invoiceMonthLength * 24 * 60 * 60 - 1;

        if($dateOpening > $dateInvoice - $monthToSeconds) {
            return true;
        }

        return false;
    }
}