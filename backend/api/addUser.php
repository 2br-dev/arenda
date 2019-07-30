<?php

$user = new Users();
$status = $user->addUser($_POST);

apiRender($status);