<?php

$invoice = new Invoices($_POST['invoice_id']);
$status = $invoice->isPaid();

apiRender($status);