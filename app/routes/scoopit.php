<?php
/**
 * Authorise user and log them to their Scoopit account.
 */
$app->get('/scoopit/login', function() use ($app) {
    require '../app/models/Scoopit.php';
    $scoopit = new Scoopit($app->config('application')['scoopit']);
    $scoopit->authorise();
});

/**
 * Authenticate user's Scoopit account.
 */
$app->get('/scoopit/authenticate', function() use ($app) {
    require '../app/models/Scoopit.php';

    $scoopit = new Scoopit($app->config('application')['scoopit']);

    if(isset($_GET['oauth_token'])) {
        if(empty($_SESSION['SCOOPIT_ACCESS_TOKEN'])) {
            $scoopit->getAccessToken();
            $app->redirect('/');
        }
        else $app->redirect('/');
    }
    else{
        $scoopit->authorise();
    }
});

/**
 * Load selected scoopit topic.
 */
$app->get('/scoopit/topic/:id', function($id) use ($app) {
    require '../app/models/Scoopit.php';

    $scoopit = new Scoopit($app->config('application')['scoopit']);
    $topic = $scoopit->getTopic($id);

    echo json_encode(array('result' => $topic));
});