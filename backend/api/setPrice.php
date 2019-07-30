<?php

$contract = new Contracts($_POST['contract_id']);
$status = $contract->setPrice($_POST['price']);

apiRender($status);