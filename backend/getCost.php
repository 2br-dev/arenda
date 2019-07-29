<?php
//
require_once $_SERVER['DOCUMENT_ROOT'] . '/core/path.php';
require_once API_RENDER;
require_once CLASSES . 'Contracts.php';
//
$contract = new Contracts();
$cost = $contract->getCost($_POST['contract_id']);
//
apiRender($cost);