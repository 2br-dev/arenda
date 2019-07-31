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
    $DATE = new PrepareDate();
    $userContracts = $contracts->getContract($userId);
    foreach ($userContracts as $userContract) {
        $today = date('U');
        $dateClosure = date('U', strtotime($userContract['date_closure']));
        $result[] = [
            'id' => (int) $userContract['id'],
            'name' => $userContract['name'],
            'dateOpening' => $DATE->prepare($userContract['date_opening']),
            'dateClosure' => $DATE->prepare($userContract['date_closure']),
            'status' => ($today > $dateClosure) ? 0 : 1,
            'invoices' => getInvoices($userContract['id'])
        ];
    }

    return $result;
}


function getInvoices($contractId)
{
    $result = [];
    $DATE = new PrepareDate();
    $invoices = new Invoices();
    $invoices = $invoices->getInvoice($contractId);
    foreach ($invoices as $invoice) {
        $Computation = new Computation();

        $result[] = [
            'id'     => $invoice['id'],
            'date'   => $DATE->prepare($invoice['date']),
            'amount' => $invoice['amount'],
            'status' => $Computation->isPaidInvoice($invoice['id'])
        ];
    }

    return $result; 
}




