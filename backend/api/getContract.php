<?php

$contract = new Contracts($_POST['contract_id']);
$list = $contract->getContract();

apiRender($list);