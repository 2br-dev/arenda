<?php

$Computation = new Computation();
$value = $Computation->isPaidInvoice($_POST['invoice_id']);

apiRender($value);