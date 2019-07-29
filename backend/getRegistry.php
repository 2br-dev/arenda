<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/backend/core/path.php';
require_once HEADERS;
require_once API_RENDER;
require_once CLASSES . 'Users.php';
require_once CLASSES . 'Contracts.php';
require_once CLASSES . 'Invoices.php';

$users = new Users();
$users = $users->getUser();

$registry = [];
foreach ($users as $user) {
    $registry[] = [
        'id' => (int) $user['id'],
        'name' => $user['name'],
        'contracts' => parseContracts($user['id'])
    ];
}

sleep(1);

apiRender($registry);




function parseContracts($id)
{
    $result = [];
    $contracts = new Contracts();
    $userContracts = $contracts->getContract($id);
    foreach ($userContracts as $userContract) {
        $today = date('U');
        $dateClosure = date('U', strtotime($userContract['date_closure']));
        $result[] = [
            'id' => (int) $userContract['id'],
            'name' => $userContract['name'],
            'dateOpening' => $userContract['date_opening'],
            'dateClosure' => $userContract['date_closure'],
            'status' => ($today > $dateClosure) ? 1 : 0,
            'invoices' => getInvoices($userContract['id'])
        ];
    }

    return $result;
}


function getInvoices($id)
{
    $result = [];
    $invoices = new Invoices();
    $invoices = $invoices->getInvoice(NULL, $id);
    foreach ($invoices as $invoice) {
        $result[] = [
            'id'     => $invoice['id'],
            'date'   => $invoice['date'],
            'amount' => $invoice['amount']
        ];
    }

    return $result; 
}




