<?php

require_once TRAITS . 'MakeUnix.php';

class Computation 
{
    use MakeUnix;

    private $DB = null;
    static private $invoicesTable = 'invoices';

    function __construct()
    {
        $this->DB = $this->DB = DB::connect();
    }


    /** возвращает не оплачен/оплачен платеж 
     * @return array
    */
    public function isPaidInvoice($invoiceId = null)
    {
        if(is_null($invoiceId) || (int) $invoiceId <= 0) {
            return null;
        }
        $contractId = $this->getContractIdByInvoiceId($invoiceId); // id договора выставленного счета
        
        $Invoices = new Invoices();
        $allInvoices = $Invoices->getInvoice($contractId); // все счета по договору

        $Payments = new Payments();
        $allPayments = $Payments->getPayment($contractId); // все платежи по договору

        return $this->analysisInvoices($contractId, $invoiceId, $allInvoices, $allPayments, 'isPaidInvoice');
    }


    // ======================================================================================================

    
    /** возвращает id договора по id счета 
     * @return int
    */
    private function getContractIdByInvoiceId($invoiceId = null)
    {
        if(is_null($invoiceId) || (int) $invoiceId <= 0) {
            return null;
        }

        $query = "SELECT contract_id FROM " . self::$invoicesTable . " WHERE id = :id";
        $params = [
            ':id' => $invoiceId
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


    /** Сумма всех платежей по договору 
     * @param array $payments
     * @return int
    */
    private function getSumPayments($payments = [])
    {
        if(empty($payments)) {
            return 0;
        }
        
        $result = 0;

        foreach ($payments as $payment) {
            $result  += (double) $payment['payt']; 
        }
        
        return $result;
    }


    /** добавляет к дате
     * @param date $date - YYYY-MM-DD
     * @param int $numberOfDays
     * example: 2000-01-01 00:00:00 + 5 дней => 2000-05-01 23:59:59
     */
    private function addToDate($date = null, $numberOfDays = 0) 
    {
        if(is_null($date)) {
            return null;
        }

        return date('U', strtotime('+' . $numberOfDays . ' days' , $date)) - 1; 
    }


    /** проверяет спецефичность цены
     * @return bool
     */
    private function isSpecificInvoiceAmount($value = null) 
    {
        if(is_null($value)) {
            return false;
        }
        return true;
    }


    /** */
    private function isDiscountPayt($paytDate, $discountZone)
    {
        if($paytDate <= $discountZone) {
            return true;
        }
        return false;
    }


    /** */
    private function paytComplete($flag, $payt, $amount, $amountDiscount)
    {
        if($flag && $payt >= $amountDiscount) {
            return true; // оплачено по дисконту
        }

        if(!$flag && $payt >= $amount) {
            return true; // оплачено без дисконта
        }

        return false;
    }


    /** Возвращает сумму с учетом дисконта 
     * @param double $amount
     * @param double $dicount
     * @return double
    */
    private function getAmountWithDiscount($amount = null, $dicount = 0)
    {
        if(is_null($amount)) {
            return 0;
        }
        
        return ceil($amount - ($amount * $dicount / 100));
    } 


    /** проходит по счетам и платежам */
    private function analysisInvoices($contractId = null, $invoiceId = null, $invoices = [], $payments = [], $type = null)
    {
        // доступные механизмы обработки
        $drivers = [
            'isPaidInvoice',
        ];
        $DRIVER = null;
        
        if(is_null($type) || is_null($contractId) || is_null($invoiceId)) {
            return null;
        }

        if(in_array($type, $drivers)) {
            $DRIVER = $type;
        } else {
            return null;
        }

        $dateToday = date('U'); // сегодняшняя дата в Unix
        $paymentSum = $this->getSumPayments($payments); // сумма всех существующих платежей по договору
        
        $Contracts = new Contracts($contractId);
        $thisContractData = array_shift( $Contracts->getContract() ); // вся информация по договору
        
        $contractConditions = [
            'priceValue'      => (double) $thisContractData['price'],                 // стоимость
            'discountValue'   => (double) $thisContractData['discount'],              // дисконт (в %)
            'discountZone'    =>    (int) $thisContractData['discount_payment_zone'], // количество дней от выстовления счета, где действует дисконт 
            'paymentZone'     =>    (int) $thisContractData['payment_zone']           // количество дней от выстовления счета, на оплату БЕЗ пени
        ];

        $lastPaymentDate = null;
        foreach ($invoices as $key => $invoice) {
            $id = (int) $invoice['id'];

            $invoiceDate = $this->makeUnix($invoice['date']);
            $invoicePaymentZone = $this->addToDate($invoiceDate, $contractConditions['paymentZone']);
            $invoiceDiscountZone = $this->addToDate($invoiceDate, $contractConditions['discountZone']);

            $specificInvoiceAmountFlag = $this->isSpecificInvoiceAmount($invoice['specific_amount']);

            $invoiceAmount = (double) $invoice['amount']; // сумма по счету (не мутирует)
            $invoiceAmountDiscount = $this->getAmountWithDiscount($invoiceAmount, $contractConditions['discountValue']); // сумма по счету (не мутирует)
            if($invoiceAmount !== $contractConditions['priceValue'] && !$specificInvoiceAmountFlag) {
                $invoiceAmountDiscount = $invoiceAmount;
            }

            $tempAmount = $invoiceAmount; // сумма по счету для мутации
            $tempAmountDiscount = $invoiceAmountDiscount; // сумма по счету с дисконтом для мутации
            
            $discontFlag = false; // в итоге определяет, с дисконтом или без
            $lastPayment = false; // true если баланс уходит в минус

            //-------------------------------------------
            switch ($DRIVER) {
                case $drivers[0]: {
                    if(!empty($payments)) {
                        while(true) {
                            if(empty($payments)) { // опустел массив с платежами платежи
                                $lastPayment = true;  // баланс уйдет в минус
                                break; // выход из while
                            }

                            $payment = array_shift($payments); // забирает оплату из массива для обработки
                            
                            $lastPaymentDate = $this->makeUnix($payment['date']); // дата оплаты
                            $payt = (double) $payment['payt']; // сумма оплаты
                            
                            // оплата по дисконту или нет
                            $discontFlag = $this->isDiscountPayt($lastPaymentDate, $invoiceDiscountZone);
                            
                            if($this->paytComplete($discontFlag, $payt, $tempAmount, $tempAmountDiscount)) {
                                break;
                            }
                            
                            $tempAmount        -= $payt; // если одной оплаты мало для погашения, то используется накопитель
                            $tempAmountDiscont -= $payt; // если одной оплаты мало для погашения, то используется накопитель
                        }
                    } 
                    
                    if($lastPayment || empty($payments)) {
                        if($lastPaymentDate <= $invoiceDiscountZone) { // если дата последней оплаты раньше или равна дисконтной зоне текущего счета
                            if($dateToday <= $invoiceDiscountZone || $paymentSum - $invoiceAmountDiscount >= 0) { // если сегодняшняя дата в рамках дисконтной зоны текущего счета ИЛИ на баласе достаточно средств для погашения текущего счета
                                $discontFlag = true;
                            }
                        }
                    }
                    
                    if($discontFlag) { // если попадаем в дисконтную зону
                        $paymentSum -= $invoiceAmountDiscount; // от общей суммы отнимаем сумму с дисконтом
                    } else {
                        $paymentSum -= $invoiceAmount; // от общей суммы отнимаем сумму без дисконтом
                    } 

                    if($id === (int) $invoiceId) {
                        if($discontFlag && $paymentSum >= 0) {
                            return [
                                'isPayt' => 1,
                                'discount' => 1,
                                'amount' => $invoiceAmountDiscount
                            ];
                        } else if($paymentSum >= 0) {
                            return [
                                'isPayt' => 1,
                                'discount' => 0,
                                'amount' => $invoiceAmount
                            ];
                        } else {
                            return [
                                'isPayt' => 0,
                                'discount' => 0,
                                'amount' => $invoiceAmount
                            ];
                        }
                    }
                    
                    break;
                }
            }
            //-------------------------------------------
        }
        
        return $contractConditions;
    }
}