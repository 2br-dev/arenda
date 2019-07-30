<?php

$balance = new Balance();
$value = $balance->getBalance($_POST['user_id'], $_POST['contract_id']);

apiRender($value);