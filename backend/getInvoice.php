<?php
//
require_once $_SERVER['DOCUMENT_ROOT'] . '/backend/core/path.php';
require_once API_RENDER;
require_once CLASSES . 'Invoices.php';
//
$invoice = new Invoices();
$list = $invoice->getInvoice($_POST['user_id'], $_POST['contract_id'], $_POST['invoice_id']);
//
apiRender($list);