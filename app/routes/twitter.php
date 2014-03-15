<?php
/**
 * Handle retweet action of the user by sending a POST 
 * request to Twitter.
 */
$app->post('/retweet', function() use ($app) {
    require '../app/models/Twitter.php';

    $twitter = new Twitter();
    $tweet_id = $app->request->post('id');

    if($twitter->postRetweet($tweet_id)) {
        echo json_encode(array('result' => true));
    }
    else echo json_encode(array('result' => false));
});

/**
 * Handle favorite action fo the user by sending a POST 
 * request to Twitter.
 */
$app->post('/favorite', function() use ($app) {
    require '../app/models/Twitter.php';

    $twitter = new Twitter();
    $tweet_id = $app->request->post('id');

    if($twitter->postFavorite($tweet_id)) {
        echo json_encode(array('result' => true));
    }
    else echo json_encode(array('result' => false));
});
?>