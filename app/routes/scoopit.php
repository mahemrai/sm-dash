<?php
$app->get('/scoopit/authenticate', function() use ($app) {
    require '../app/models/Scoopit.php';

    $scoopit = new Scoopit();

    if(isset($_GET['oauth_token'])) {
        if(empty($_SESSION['SCOOPIT_ACCESS_TOKEN'])) {
            $scoopit->access();
            $app->redirect('/sm-dash/public');
        }
        else $app->redirect('/sm-dash/public');
    }
    else{
        $scoopit->authorise();
    }
});
?>