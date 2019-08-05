<?php

$balance = new Balance();
$value = $balance->getBalance($_POST['contract_id']);

apiRender($value);