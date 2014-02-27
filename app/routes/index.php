<?php
$app->get('/', function() use ($app) {
	require '../app/models/Twitter.php';
	require '../app/utils/TwitterResponseManipulator.php';

	$twitter = new Twitter();
	$tweets = $twitter->getHomeTimeline();

	$view_data = array(
		'tweets' => TwitterResponseManipulator::extract($tweets)
	);
	
	$app->render(
		'home.html.twig', 
		$view_data
	);
});
?>