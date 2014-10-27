<?php
$app->get('/scoopit/login', function() use ($app) {
    require '../app/models/Scoopit.php';
    $scoopit = new Scoopit();
    $scoopit->authorise();
});

$app->get('/scoopit/authenticate', function() use ($app) {
    require '../app/models/Scoopit.php';

    $scoopit = new Scoopit();

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

$app->get('/scoopit/topic/:id', function($id) use ($app) {
    require '../app/models/Scoopit.php';

    $scoopit = new Scoopit();
    $topic = $scoopit->getTopic($id);

    echo json_encode(array('result' => $topic));
});