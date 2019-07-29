<?php
//
require_once $_SERVER['DOCUMENT_ROOT'] . '/core/path.php';
require_once API_RENDER;
require_once CLASSES . 'Payments.php';
//
$payment = new Payments();
$status = $payment->addPayment($_POST);
//
apiRender($status);