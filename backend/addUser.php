<?php
//
require_once $_SERVER['DOCUMENT_ROOT'] . '/core/path.php';
require_once API_RENDER;
require_once CLASSES . 'Users.php';
//
$user = new Users();
$status = $user->addUser($_POST);
//
apiRender($status);