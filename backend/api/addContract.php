<?php

$contract = new Contracts();
$status = $contract->addContract($_POST);
//
apiRender($status);