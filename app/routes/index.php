<?php
/**
 * Main routes for the application.
 * @package routes
 * @author Mahendra Rai
 */

/**
 * Authorise user with Scoop.it if required and load 
 * feeds from Twitter and Scoop.it.
 */
$app->get('/', function() use ($app) {
    require '../app/models/Twitter.php';
    require '../app/models/Scoopit.php';

    $scoopit = new Scoopit();

    //authorise user with scoopit if the access token is no longer 
    //valid otherwise load scoops for the user
    if(empty($_SESSION['SCOOPIT_ACCESS_TOKEN'])) {
        $scoopit->authorise();
    }
    else $scoops = $scoopit->getCompilation(20);

    $twitter = new Twitter();
    //retrieve hometimeline tweets for the user
    $tweets = $twitter->getHomeTimeline();

    $view_data = array(
        'tweets' => $tweets,
        'scoops' => $scoops
    );
    
    $app->render('home.html.twig', $view_data);
});

/**
 * Load user's twitter timeline feed.
 */
$app->get('/twitter', function() use ($app) {
    require '../app/models/Twitter.php';

    $twitter = new Twitter();
    $stats = $twitter->getUserStats();
    $tweets = $twitter->getUserTimeline();

    $view_data = array(
        'stats' => $stats,
        'tweets' => $tweets
    );

    $app->render('twitter.html.twig', $view_data);
});
