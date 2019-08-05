<?php
require_once 'core/path.php';
require_once HEADERS;
require_once ROUTER;

require_once CLASSES . 'Users.php';
require_once CLASSES . 'Contracts.php';
require_once CLASSES . 'Invoices.php';
require_once CLASSES . 'Payments.php';
require_once CLASSES . 'Balance.php';
require_once CLASSES . 'Computation.php';
require_once CLASSES . 'PrepareDate.php';
require_once CLASSES . 'Rooms.php';

require_once API_RENDER;

new Router();