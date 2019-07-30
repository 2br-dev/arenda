<?php
//
require_once $_SERVER['DOCUMENT_ROOT'] . '/backend/core/path.php';
require_once API_RENDER;
require_once CLASSES . 'Payments.php';
//
$payment = new Payments();
$list = $payment->getPayment($_POST['user_id'], $_POST['contract_id'], $_POST['payment_id']);
//
apiRender($list);