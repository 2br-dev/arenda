<?php

$contract = new Contracts($_POST['contract_id']);
$status = $contract->setDiscount($_POST['discount']);

apiRender($status);