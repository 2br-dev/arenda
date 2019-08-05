<?php
require_once DB;
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
    //
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
        if(!is_null($invoiceId) && (int) $invoiceId > 0) {
            $this->invoiceId = (int) $invoiceId;
        }
    }


    /** Возвращает список выстовленных счетов 
     * @param int $contractId
     * @return array
    */
    public function getInvoice($contractId = null)
    {
        if(!is_null($contractId)) {
            $contractId = $this->makeInt($contractId);
        }

        $query = 'SELECT * FROM ' . self::$table;
        $params = [];
 
        $flagAnd = false;
        
        if(!is_null($contractId)) {
            if(!$flagAnd) {
                $query .= ' WHERE';
            } else {
                $query .= ' AND';
            }         
            $query .= ' contract_id = :contract_id';
            $params[':contract_id'] = $contractId;
            $flagAnd = true;
        }
                
        if(!is_null($this->invoiceId) && is_null($this->contractId)) {
            if(!$flagAnd) {
                $query .= ' WHERE';
            } else {
                $query .= ' AND';
            }
            //
            $query .= ' id = :id';
            $params[':id'] = $this->invoiceId;
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
    */ 
    public function addinvoice($props = []) 
    {
        
        $conditions = [ // Требуемые параметры
            'contract_id' => null,
            'date' => null,
            'amount' => null, // по умолчанию
            'specific_amount' => null, // по умолчанию
        ];

        if(!is_null($props['contract_id'])) {
            $conditions['contract_id'] = $props['contract_id'];
        } else {
            return 0;
        }

        if(!is_null($props['date'])) {
            $conditions['date'] = $props['date'];
        } else {
            return 0;
        }

        if(!is_null($props['amount'])) {
            $conditions['amount'] = $props['amount'];
        } else {
            $contract = new Contracts($conditions['contract_id']);
            $thisContract = array_shift( $contract->getContract() );
            $dateOpening =  $this->makeUnix($thisContract['date_opening']);
            $dateThisInvoice = $this->makeUnix($conditions['date']);

            if($this->isNotFullMonth($dateOpening, $dateThisInvoice)) {
                $conditions['amount'] = $this->getPartialAmount((double) $thisContract['price'], $dateThisInvoice, $dateOpening);
                $conditions['specific_amount'] = 1;
            }

            if(is_null($conditions['specific_amount'])) {
                $price = (double) $thisContract['price'];
                
                if(is_null($price)) {
                    return 0;
                }
                
                $conditions['amount'] = $price;
            }
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


    /** Проверяет оплачен счет или нет 
     * @return int 1/0
     * 
    */
    public function isPaid()
    {
        if(is_null($this->invoiceId)) {
            return null;
        }

        $contractId = $this->getParentContract();
        
        $allInvoices = $this->getInvoice($contractId);
        return $allInvoices;
        
    }


    /** Возвращает id договора, которому принадлежит счет
     * @return int 
     * 
    */
    public function getParentContract()
    {
        if(is_null($this->invoiceId)) {
            return null;
        }
        $query = 'SELECT contract_id FROM ' . self::$table . ' WHERE id = :id';
        $params = [
            ':id' => $this->invoiceId
        ];

        $stmt = $this->DB->prepare($query);
        if(!$stmt->execute($params)) {
            return null;
        }
        $response = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if(empty($response)) {
            return null;
        }

        $response = array_shift($response);

        return (int) $response['contract_id'];
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
    private function getPartialAmount($amount = null, $dateThis = null, $dateOpening = null)
    {
        if(is_null($amount) || is_null($dateThis) || is_null($dateOpening)) {
            return null;
        }
        
        $monthThis = date('n', $dateThis);
        $amonthOpening = date('n', $dateOpening);
        if(abs($monthThis - $amonthOpening) > 1) {
            return 0;
        }
        
        $dayStart = date('d', $dateOpening);
        $dayInvoice = date('d', $dateThis);
        if($dateOpening > $dayInvoice && $monthThis === $amonthOpening) {
            return 0;
        }

        $countDays = date('t', $dateOpening);
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