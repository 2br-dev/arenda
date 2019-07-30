<?php

$invoice = new Invoices();
$status = $invoice->addinvoice($_POST);

apiRender($status);