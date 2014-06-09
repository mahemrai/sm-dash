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
    $client = new TwitterApiExchange($twitter->getApiInfo());
    $twitter->setClient($client);

    $tweets = $twitter->getHomeTimeline('https://api.twitter.com/1.1/statuses/home_timeline.json');

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
    $client = new TwitterApiExchange($twitter->getApiInfo());
    $twitter->setClient($client);

    $stats = $twitter->getUserStats('https://api.twitter.com/1.1/users/show.json');
    $tweets = $twitter->getUserTimeline('https://api.twitter.com/1.1/statuses/user_timeline.json');

    $view_data = array(
        'stats' => $stats,
        'tweets' => $tweets
    );

    $app->render('twitter.html.twig', $view_data);
});

/**
 * Load user's scoopit feed.
 */
$app->get('/scoopit', function() use ($app) {
    require '../app/models/Scoopit.php';

    $scoopit = new Scoopit();
    $topics = $scoopit->getUserTopics();
    $topic = $scoopit->getTopic();

    $view_data = array(
        'topics' => $topics,
        'topic' => $topic
    );

    $app->render('scoopit.html.twig', $view_data);
});

/**
 * Load settings page for the user.
 */
$app->get('/settings', function() use ($app) {
    $app->render('settings.html.twig');
});
