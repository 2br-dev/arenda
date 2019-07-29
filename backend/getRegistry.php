<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/backend/core/path.php';
require_once API_RENDER;
require_once CLASSES . 'Users.php';
require_once CLASSES . 'Contracts.php';
require_once CLASSES . 'Invoices.php';

$users = new Users();
$contracts = new Contracts();
$invoices = new Invoices();


$users = $users->getUser();

$registry = [];
foreach ($users as $user) {
    $registry[$user['id']] = [
        'name' => $user['name'],
        'contracts' => []
    ];

    $userContracts = $contracts->getContract($user['id']);
    foreach ($userContracts as $userContract) {
        $registry[$user['id']]['contracts'][$userContract['id']] = [
            'name' => $userContract['name'],
            'dateOpening' => $userContract['date_opening'],
            'dateClosure' => $userContract['date_closure'],
            'status' => 1,
            'invoices' => []
        ];


        $today = date('U');
        $dateClosure = date('U', strtotime($userContract['date_closure']));
        if($today > $dateClosure) {
            $registry[$user['id']]['contracts'][$userContract['id']]['status'] = 0;
        }
       

        $contractInvoices = $invoices->getInvoice(NULL, $userContract['id']);
        foreach ($contractInvoices as $contractInvoice) {
            $registry[$user['id']]['contracts'][$userContract['id']]['invoices'][$contractInvoice['id']] = [
                'date' => $contractInvoice['date'],
                'amount' =>  $contractInvoice['amount']
            ];
        }
    }

}

sleep(3);

apiRender($registry);
