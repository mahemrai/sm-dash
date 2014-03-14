<?php
/**
 * Load main page.
 */
$app->get('/', function() use ($app) {
    require '../app/models/Twitter.php';
    require '../app/models/Scoopit.php';

    $scoopit = new Scoopit();
    if(empty($_SESSION['SCOOPIT_ACCESS_TOKEN'])) {
        $scoopit->authorise();
    }
    else $scoops = $scoopit->getCompilation(10, false);

    $twitter = new Twitter();
    $tweets = $twitter->getHomeTimeline();

    $view_data = array(
        'tweets' => $tweets,
        'scoops' => $scoops
    );
	
    $app->render('home.html.twig', $view_data);
});
?>
