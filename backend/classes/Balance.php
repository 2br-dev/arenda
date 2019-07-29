<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/core/path.php';
require_once CLASSES . 'Contracts.php';
require_once CLASSES . 'Invoices.php';
require_once CLASSES . 'Payments.php';
//
require_once TRAITS . 'MakeInt.php';
require_once TRAITS . 'MakeUnix.php';


/** Balance class */
class Balance
{   
    // Объект класса DB
    private $DB = null;
    // Объект класса Contract
    private $Contracts = null;
    // Объект класса Contract
    private $Payments = null;
    // Объект класса Contract
    private $Invoices = null;
    

    use MakeInt;
    use MakeUnix;
    

    public function __construct()
    {   
        $this->Contracts = new Contracts();
        $this->Invoices = new Invoices();
        $this->Payments = new Payments();
    }


    /** Возвращает баланс
     * @param int $userId
    */
    public function getBalance($userId = null, $contractId = null) 
    {
        if(!is_null($userId)) {
            $userId = $this->makeInt($userId);
        } 
        
        if(!is_null($contractId)) {
            $contractId = $this->makeInt($contractId);
        } 
        
        if(is_null($userId) && is_null($contractId)) { // получено хотя бы одно из значений
            return null;
        }
        
        $balance = null;
        
        if(is_null($userId)) {
            $balance = $this->getBalanseOfContract($contractId);
        } else {
            foreach ($this->Contracts->getContract($userId) as $key => $contract) {
                $balance += $this->getBalanseOfContract($contract['id']);
            }
        }
        
        return $balance;
    }


    /** получить баланс по одному договору 
    * @param int $contractId
    * @return double  
    */
    private function getBalanseOfContract($contractId = null)
    {
        if(is_null($contractId)) {
            return null;
        }
        
        $dateToday = date('U'); // сегодняшняя дата в формате Unix
        $allPayments = $this->Payments->getPayment(null, $contractId); // все платежи по договору
        $balance = $this->getSumPayments($allPayments); // сумма всех платежей

        $thisContractData = array_shift( $this->Contracts->getContract(null, $contractId) ); // вся информация по договору
        
        $contractConditions = [
            'priceValue'      => (double) $thisContractData['price'],                 // стоимость
            'discountValue'   => (double) $thisContractData['discount'],              // дисконт (в %)
            'discountZone'    =>    (int) $thisContractData['discount_payment_zone'], // количество дней от выстовления счета, где действует дисконт 
            'paymentZone'     =>    (int) $thisContractData['payment_zone']           // количество дней от выстовления счета, на оплату БЕЗ пени
        ];
        
        $__lastPaymentDate = null; // дата платежа (текущая, а когда переберу все платежи - последняя)
        
        $allInvoices = $this->Invoices->getInvoice(null, $contractId); // Все счета по договору (отсортированные по дате)
        
        foreach ($allInvoices as $invoice) {
            $invoiceDate = $this->makeUnix($invoice['date']);
            $invoicePaymentZone = $this->addToDate($invoiceDate, $contractConditions['paymentZone']);
            $invoiceDiscountZone = $this->addToDate($invoiceDate, $contractConditions['discountZone']);

            $specificInvoiceAmount = (double) $invoice['specific_amount']; // сумма счета за месяц без дисконта
            $invoiceAmountFull     = (double) $invoice['amount']; // сумма счета за месяц без дисконта
            $invoiceAmountDiscount = $this->getAmountWithDiscount($invoiceAmountFull, $contractConditions['discountValue']); // сумма счета за месяц с дисконтом
            if($invoiceAmountFull !== $contractConditions['priceValue'] && is_null($specificInvoiceAmount)) { // если сумма отличается от прописанной в договоре, то С ДИСКОНТОМ === БЕЗ ДИСКОНТА
                $invoiceAmountDiscount = (double) $invoice['amount']; 
            }
            
            $tempAmountFull    = $invoiceAmountFull;     // для получения разности без дисконта
            $tempAmountDiscont = $invoiceAmountDiscount; // для получения разности с дисконтом
            
            $discontFlag = false; // в итоге определяет, с дисконтом или без
            $lastPayment = false; // true если баланс уходит в минус
            
            if(!empty($allPayments)) { // если есть не обработанные платежи
                
                while(true) { // завершается по breack
                    
                    if(empty($allPayments)) { // опустел массив с платежами платежи
                        $lastPayment = true;  // баланс уйдет в минус
                        break; // выход из while
                    }
                    
                    $payment = array_shift($allPayments); // забирает оплату из массива для обработки
                    $__lastPaymentDate = $this->makeUnix($payment['date']); // дата оплаты
                    $paymentPayt = (double) $payment['payt']; // сумма оплаты
                     
                    if($__lastPaymentDate <= $invoiceDiscountZone) { // если платеж совершен раньше, чем заканчивается дисконт (может быть и за несколько месяцев до выставления счета)
                        $discontFlag = true; 
                    } else if($__lastPaymentDate <= $invoicePaymentZone) { // если платеж совершен позже, чем заканчивается дисконт, но не дошел до пени
                        $discontFlag = false;
                    } else { // В РАЗРАБОТКЕ (если включается пеня)
                        $discontFlag = false;
                    }
                    
                    if((!$discontFlag && $paymentPayt >= $tempAmountFull) || ($discontFlag && $paymentPayt >= $tempAmountDiscont)) { // если платеж гасит счет по остаткам (по дисконту или без дисконта)
                        break; // выход из while
                    }
                    
                    $tempAmountFull    -= $paymentPayt; // если одной оплаты мало для погашения, то используется накопитель
                    $tempAmountDiscont -= $paymentPayt; // если одной оплаты мало для погашения, то используется накопитель
                }  
            } 
            
            if($lastPayment || empty($allPayments)) { // если оплаты закончились не погасив счет ИЛИ массив с оплатами пуст
                if($__lastPaymentDate <= $invoiceDiscountZone) { // если дата последней оплаты раньше или равна дисконтной зоне текущего счета
                    if($dateToday <= $invoiceDiscountZone || $balance - $invoiceAmountDiscount >= 0) { // если сегодняшняя дата в рамках дисконтной зоны текущего счета ИЛИ на баласе достаточно средств для погашения текущего счета
                        $discontFlag = true;
                    } else {
                        $discontFlag = false;
                    }
                } else if($__lastPaymentDate <= $invoicePaymentZone) { // если пропустили дисконт, но в рамках без пени
                    $discontFlag = false;
                } else { // В РАЗРАБОТКЕ (если включается пеня)
                    $discontFlag = false;
                }
            }
            
            if($discontFlag) { // если попадаем в дисконтную зону
                $balance = $balance - $invoiceAmountDiscount; // от общей суммы отнимаем сумму с дисконтом
            } else {
                $balance = $balance - $invoiceAmountFull; // от общей суммы отнимаем сумму без дисконтом
            }    
        }
        
        return $balance;
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
}