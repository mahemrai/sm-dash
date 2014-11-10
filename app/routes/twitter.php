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

    $twitter = new Twitter($app->config('application')['twitter']['username']);
    $client = new TwitterAPIExchange(
        $twitter->getApiInfo($app->config('application')['twitter'])
    );
    $twitter->setClient($client);

    $url = 'https://api.twitter.com/1.1/statuses/retweet/'.$tweet_id.'.json';
    $tweet_id = $app->request->post('id');

    if($twitter->postRetweet($url, $tweet_id)) {
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

    $twitter = new Twitter($app->config('application')['twitter']['username']);
    $client = new TwitterAPIExchange(
        $twitter->getApiInfo($app->config('application')['twitter'])
    );
    $twitter->setClient($client);

    $tweet_id = $app->request->post('id');
    $url = 'https://api.twitter.com/1.1/favorites/create.json';

    if($twitter->postFavorite($url, $tweet_id)) {
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

    $twitter = new Twitter($app->config('application')['twitter']['username']);
    $client = new TwitterAPIExchange(
        $twitter->getApiInfo($app->config('application')['twitter'])
    );
    $twitter->setClient($client);

    $url = 'https://api.twitter.com/1.1/statuses/update.json';
    $status = $app->request->post('status');

    if($twitter->postTweet($url, $status)) {
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

    $twitter = new Twitter($app->config('application')['twitter']['username']);
    $client = new TwitterAPIExchange(
        $twitter->getApiInfo($app->config('application')['twitter'])
    );
    $twitter->setClient($client);

    $tweet_id = $app->request->post('id');
    $url = 'https://api.twitter.com/1.1/statuses/destroy/'.$tweet_id.'.json';

    if($twitter->deleteTweet($url, $tweet_id)) {
        echo json_encode(array('result' => true));
    }
    else echo json_encode(array('result' => false));
});
