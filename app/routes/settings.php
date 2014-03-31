<?php

/**
 * Load details for selected account.
 */
$app->get('/settings/account/:id', function($id) use ($app) {
    require '../app/models/Accounts.php';

    $account = new Accounts();
    $info = $account->getApiAccount($id);

    $app->render('account.html.twig', $info);
});

/**
 * Handle request for modifying details of the selected account.
 */
$app->post('/settings/:id/edit', function($id) use ($app) {
    require '../app/models/Accounts.php';

    $accounts = new Accounts();
    $accounts->saveAccountDetails($app->request->post());

    echo json_encode(array('result' => true));
});

/**
 * Handle request for saving details of the new account.
 */
$app->post('/settings/:id/add', function($id) use ($app) {
    require '../app/models/Accounts.php';

    $accounts = new Accounts();
    $accounts->saveAccountDetails($app->request->post());

    echo json_encode(array('result' => true));
});

/**
 * Handle request for retrieving data for the selected account.
 */
$app->get('/accounts/:id', function($id) use ($app) {
    require '../app/models/Accounts.php';

    $account = new Accounts();
    $info = $account->getApiAccount($id);

    echo json_encode(
        array('data' => $info)
    );
});