<?php

$payment = new Payments();
$status = $payment->addPayment($_POST);

apiRender($status);