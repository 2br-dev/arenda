<?php

$invoice = new Invoices($_POST['invoice_id']);
$list = $invoice->getInvoice($_POST['contract_id']);

apiRender($list);