<?php

$Rooms = new Rooms();
$array = $Rooms->getRooms();

apiRender($array);