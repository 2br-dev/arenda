<?php
//
require_once $_SERVER['DOCUMENT_ROOT'] . '/backend/core/path.php';
require_once API_RENDER;
require_once CLASSES . 'Balance.php';
//
$balance = new Balance();
$value = $balance->getBalance($_POST['user_id'], $_POST['contract_id']);
//
apiRender($value);