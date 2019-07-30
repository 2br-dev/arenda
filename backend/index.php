<?php
require_once 'core/path.php';
require_once ROUTER;

require_once CLASSES . 'Users.php';
require_once CLASSES . 'Contracts.php';
require_once CLASSES . 'Invoices.php';
require_once CLASSES . 'Payments.php';
require_once CLASSES . 'Balance.php';

new Router();