<?php
/**
 * Twitter related routes for handling ajax requests.
 * @package routes
 * @author Mahendra Rai
 */

/**
 * Handle retweet action of the user by sending a POST
 * request to Twitter.
 */
$app->post('/twitter/retweet', function() use ($app) {
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
$app->post('/twitter/favorite', function() use ($app) {
    require '../app/models/Twitter.php';

    $twitter = new Twitter();
    $tweet_id = $app->request->post('id');

    if($twitter->postFavorite($tweet_id)) {
        echo json_encode(array('result' => true));
    }
    else echo json_encode(array('result' => false));
});

/**
 * Handle tweet action of the user by sending a POST request
 * to Twitter.
 */
$app->post('/twitter/tweet', function() use ($app) {
    require '../app/models/Twitter.php';

    $twitter = new Twitter();
    $status = $app->request->post('status');

    if($twitter->postTweet($status)) {
        echo json_encode(array('result' => true));
    }
    else echo json_encode(array('result' => false));
});

/**
 * Handle delete action of the user by sending a POST request
 * to Twitter.
 */
$app->post('/twitter/delete', function() use ($app) {
    require '../app/models/Twitter.php';

    $twitter = new Twitter();
    $tweet_id = $app->request->post('id');

    if($twitter->deleteTweet($tweet_id)) {
        echo json_encode(array('result' => true));
    }
    else echo json_encode(array('result' => false));
});
