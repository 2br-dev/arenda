<?php
//
require_once $_SERVER['DOCUMENT_ROOT'] . '/backend/core/path.php';
require_once API_RENDER;
require_once CLASSES . 'Contracts.php';
//
$contract = new Contracts();
$list = $contract->getContract($_POST['user_id'], $_POST['contract_id']);
//
apiRender($list);