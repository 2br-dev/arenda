<?php
//
require_once $_SERVER['DOCUMENT_ROOT'] . '/backend/core/path.php';
require_once API_RENDER;
require_once CLASSES . 'Contracts.php';
//
$contract = new Contracts();
$status = $contract->addContract($_POST);
//
apiRender($status);