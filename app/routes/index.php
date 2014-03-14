<?php
/**
 * Load main page.
 */
$app->get('/', function() use ($app) {
    require '../app/models/Twitter.php';
    require '../app/models/Scoopit.php';

    $scoopit = new Scoopit();

    $twitter = new Twitter();
    $tweets = $twitter->getHomeTimeline();

    $view_data = array(
        'tweets' => $tweets
    );
	
    $app->render('home.html.twig', $view_data);
});
?>
