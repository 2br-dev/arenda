<?php

$contract = new Contracts($_POST['contract_id']);
$cost = $contract->getCost();

apiRender($cost);