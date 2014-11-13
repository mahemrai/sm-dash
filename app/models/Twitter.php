<?php

/**
 * Twitter class
 * @package models
 * @author Mahendra Rai
 */
class Twitter {
    const TWITTER = 'Twitter';
    
    private $username;
    private $client;

    public function __construct($username) {
        $this->username = $username;
    }

    /**
     * Sets client object to make calls to Twitter API.
     * @param string $username
     * @param TwitterApiExchange $client 
     */
    public function setClient(TwitterApiExchange $client) {
        $this->client = $client;
    }

    /**
     * Get home timeline tweets from Twitter.
     * @return array
     */
    public function getHomeTimeline($url) {
        $request_method = 'GET';

        $tweets = json_decode($this->client->buildOauth($url, $request_method)->performRequest());

        $data = $this->extractData($tweets);

        return $data;
    }

    /**
     * Get user tweets from Twitter.
     * @return array
     */
    public function getUserTimeline($url) {
        $request_method = 'GET';

        $tweets = json_decode($this->client->buildOauth($url, $request_method)->performRequest());

        $data = $this->extractData($tweets);

        return $data;
    }

    /**
     * Get user stats from Twitter
     * @param string $url
     * @return array
     */
    public function getUserStats($url) {
        $request_method = 'GET';

        $response = json_decode(
            $this->client->setGetfield('?screen_name='.$this->username)
                   ->buildOauth($url, $request_method)
                   ->performRequest()
        );

        $data = array(
            'name' => $response->name,
            'image' => $response->profile_image_url,
            'screen_name' => $response->screen_name,
            'location' => $response->location,
            'description' => $response->description,
            'followers' => $response->followers_count,
            'friends' => $response->friends_count,
            'listed' => $response->listed_count,
            'favourites' => $response->favourites_count,
            'statuses' => $response->statuses_count
        );

        return $data;
    }

    /**
     * Send POST request to twitter to complete user's tweet action.
     * @param string $tweet_text
     * @return boolean
     */
    public function postTweet($url, $tweet_text) {
        $request_method = 'POST';

        $postfield = array(
            'status' => $tweet_text
        );

        $response = json_decode(
            $this->client->buildOauth($url, $request_method)
                   ->setPostfields($postfield)
                   ->performRequest()
        );

        return (isset($response->errors)) ? false : true;
    }

    /**
     * Send POST request to twitter to complete user's retweet action.
     * @param int tweet_id
     * @return boolean
     */
    public function postRetweet($url, $tweet_id) {
        $request_method = 'POST';

        $postfield = array(
            'id' => $tweet_id
        );

        $response = json_decode(
            $this->client->buildOauth($url, $request_method)
                   ->setPostfields($postfield)
                   ->performRequest()
        );

        return (isset($response->errors)) ? false : true;
    }

    /**
     * Send POST request to twitter to complete user's favorite action.
     * @return boolean
     */
    public function postFavorite($url, $tweet_id) {
        $request_method = 'POST';

        $postfield = array(
            'id' => $tweet_id
        );

        $response = json_decode(
            $this->client->buildOauth($url, $request_method)
                   ->setPostfields($postfield)
                   ->performRequest()
        );

        return (isset($response->errors)) ? false : true;
    }

    /**
     * Send POST request to twitter to complete user's delete action.
     * @param int $tweet_id
     * @return bolean
     */
    public function deleteTweet($url, $tweet_id) {
        $request_method = 'POST';

        $postfield = array(
            'id' => $tweet_id
        );

        $response = json_decode(
            $this->client->buildOauth($url, $request_method)
                   ->setPostfields($postfield)
                   ->performRequest()
        );

        return (isset($response->errors)) ? false : true;
    }

    /**
     * Construct api information from the config data retrived from YAML file.
     * @return array
     */
    public function getApiInfo($api_config) {
        return array(
            'oauth_access_token' => $api_config['oauth_token'],
            'oauth_access_token_secret' => $api_config['oauth_token_secret'],
            'consumer_key' => $api_config['api_key'],
            'consumer_secret' => $api_config['api_secret'],
            'username' => $api_config['username']
        );
    }

    /**
     * Determine whether the selected tweet is a retweeted status or not and return
     * appropriate content.
     * @param object $tweet
     * @return string
     */
    public function selectTweetContent($tweet) {
        return (isset($tweet->retweeted_status)) ?
            $tweet->retweeted_status->text : $tweet->text;
    }

    /**
     * Extract tweets from the json response received from Twitter
     * @param object $tweets
     * @return array | null
     */
    public function extractData($tweets) {
        if (!empty($tweets)) {
            foreach($tweets as $tweet) {
                $data_item = array(
                    'id' => $tweet->id,
                    'created_at' => $tweet->created_at,
                    'text' => $this->selectTweetContent($tweet),
                    'user' => $tweet->user,
                    'retweet_count' => $tweet->retweet_count,
                    'favorite_count' => $tweet->favorite_count,
                    'entities' => $tweet->entities,
                    'favorited' => $tweet->favorited,
                    'retweeted' => $tweet->retweeted
                );

                $data[] = $data_item;
            }

            return $data;
        }

        return null;
    }
}
