<?php

/**
 * Handle request for modifying details of the selected account.
 */
$app->post('/settings/edit', function() use ($app) {
    require '../app/models/Accounts.php';

    $accounts = new Accounts();
    $accounts->saveAccountDetails($app->request->post());

    if($accounts) {
        echo json_encode(array('result' => true));
    }
    else echo json_encode(array('result' => false));
});

/**
 * Handle request for saving details of the new account.
 */
$app->post('/settings/create', function() use ($app) {
    require '../app/models/Accounts.php';

    $accounts = new Accounts();
    $accounts->saveAccountDetails($app->request->post());

    if($accounts) {
        echo json_encode(array('result' => true));
    }
    else echo json_encode(array('result' => false));
});

/**
 * Handle request for retrieving data for the selected account.
 */
$app->get('/accounts/:account', function($account_name) use ($app) {
    require '../app/models/Accounts.php';

    $account = new Accounts();
    $info = $account->getApiAccount($account_name);

    if(!empty($info)) {
        echo json_encode(
            array('data' => $info)
        );
    }
    else {
        echo json_encode(array('result' => false));
    }
});