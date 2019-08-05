<?php

$date = $_POST['date'];
$invoices = json_decode($_POST['invoices'], true);

$Invoices = new Invoices();
foreach ($invoices as $item) {
    $result = $Invoices->addInvoice([
        'contract_id' => $item['id'],
        'date' => $date,
        'amount' => ((double) $item['amount'] == 0) ? null : (double) $item['amount']
    ]);
    if($result == 0) {
        apiRender(0);
    }
}

apiRender(1);