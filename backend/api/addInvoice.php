<?php
//
require_once $_SERVER['DOCUMENT_ROOT'] . '/backend/core/path.php';
require_once API_RENDER;
require_once CLASSES . 'Invoices.php';
//
$invoice = new Invoices();
$status = $invoice->addinvoice($_POST);
//
apiRender($status);