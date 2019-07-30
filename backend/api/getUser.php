<?php

$user = new Users($_POST['user_id']);
$list = $user->getUser();

apiRender($list);