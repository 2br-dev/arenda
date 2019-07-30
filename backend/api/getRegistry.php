<?php

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

// sleep(1);

apiRender($registry);




function parseContracts($userId)
{
    $result = [];
    $contracts = new Contracts();
    $userContracts = $contracts->getContract($userId);
    foreach ($userContracts as $userContract) {
        $today = date('U');
        $dateClosure = date('U', strtotime($userContract['date_closure']));
        $result[] = [
            'id' => (int) $userContract['id'],
            'name' => $userContract['name'],
            'dateOpening' => $userContract['date_opening'],
            'dateClosure' => $userContract['date_closure'],
            'status' => ($today > $dateClosure) ? 0 : 1,
            'invoices' => getInvoices($userContract['id'])
        ];
    }

    return $result;
}


function getInvoices($contractId)
{
    $result = [];
    $invoices = new Invoices();
    $invoices = $invoices->getInvoice($contractId);
    foreach ($invoices as $invoice) {
        $result[] = [
            'id'     => $invoice['id'],
            'date'   => $invoice['date'],
            'amount' => $invoice['amount'],
            'status' => 0
        ];
    }

    return $result; 
}




