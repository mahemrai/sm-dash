<?php
/**
 * 
 */
$app->post('/retweet' function() use ($app) {
    require '../app/models/Twitter.php';

    $twitter = new Twitter();
});

/**
 * 
 */
$app->post('/favorite' function() use ($app) {
    require '../app/models/Twitter.php';

    $twitter = new Twitter();
})
?>