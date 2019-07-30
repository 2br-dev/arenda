<?php

$payment = new Payments($_POST['payment_id']);
$list = $payment->getPayment($_POST['contract_id']);

apiRender($list);