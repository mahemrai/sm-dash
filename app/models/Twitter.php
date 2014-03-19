<?php

/**
 * Twitter class
 * @package models
 * @author Mahendra Rai
 */
class Twitter {
    const TWITTER = 'Twitter';
    const USERNAME = 'mahemrai';

    private $settings;
    
    //constructor
    public function __construct() {
        $this->getApiInfo();
    }

    /**
     * Get home timeline tweets from Twitter.
     * @return array
     */
    public function getHomeTimeline() {
        $url = 'https://api.twitter.com/1.1/statuses/home_timeline.json';
        $request_method = 'GET';

        $client = new TwitterApiExchange($this->settings);
        $tweets = json_decode($client->buildOauth($url, $request_method)->performRequest());

        $data = $this->extractData($tweets);
        
        return $data;
    }

    /**
     * Get user tweets from Twitter.
     * @return array
     */
    public function getUserTimeline() {
        $url = 'https://api.twitter.com/1.1/statuses/user_timeline.json';
        $request_method = 'GET';

        $client = new TwitterApiExchange($this->settings);
        $tweets = json_decode($client->buildOauth($url, $request_method)->performRequest());

        $data = $this->extractData($tweets);

        return $data;
    }

    public function getUserStats() {
        $url = 'https://api.twitter.com/1.1/users/show.json';
        $request_method = 'GET';

        $client = new TwitterApiExchange($this->settings);
        $response = json_decode(
            $client->setGetfield('?screen_name=mahemrai')
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
    public function postTweet($tweet_text) {
        $url = 'https://api.twitter.com/1.1/statuses/update.json';
        $request_method = 'POST';

        $postfield = array(
            'status' => $tweet_text
        );

        $client = new TwitterApiExchange($this->settings);
        $response = json_decode(
            $client->buildOauth($url, $request_method)
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
    public function postRetweet($tweet_id) {
        $url = 'https://api.twitter.com/1.1/statuses/retweet/'.$tweet_id.'.json';
        $request_method = 'POST';

        $postfield = array(
            'id' => $tweet_id
        );

        $client = new TwitterApiExchange($this->settings);
        $response = json_decode(
            $client->buildOauth($url, $request_method)
                   ->setPostfields($postfield)
                   ->performRequest()
        );

        return (isset($response->errors)) ? false : true;
    }

    /**
     * Send POST request to twitter to complete user's favorite action.
     * @return boolean
     */
    public function postFavorite($tweet_id) {
        $url = 'https://api.twitter.com/1.1/favorites/create.json';
        $request_method = 'POST';

        $postfield = array(
            'id' => $tweet_id
        );

        $client = new TwitterApiExchange($this->settings);
        $response = json_decode(
            $client->buildOauth($url, $request_method)
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
    public function deleteTweet($tweet_id) {
        $url = 'https://api.twitter.com/1.1/statuses/destroy/'.$tweet_id.'.json';
        $request_method = 'POST';

        $postfield = array(
            'id' => $tweet_id
        );

        $client = new TwitterApiExchange($this->settings);
        $response = json_decode(
            $client->buildOauth($url, $request_method)
                   ->setPostfields($postfield)
                   ->performRequest()
        );

        return (isset($response->errors)) ? false : true;
    }

    /**
     * Retrieve api information from the database.
     * @return array
     */
    protected function getApiInfo() {
        $api_info = ORM::for_table('sm_accounts')
            ->where('account', self::TWITTER)
            ->find_one()
            ->as_array();

        $this->settings = array(
            'oauth_access_token' => $api_info['oauth_token'],
            'oauth_access_token_secret' => $api_info['oauth_token_secret'],
            'consumer_key' => $api_info['api_key'],
            'consumer_secret' => $api_info['api_secret']
        );
    }

    /**
     * Determine whether the selected tweet is a retweeted status or not and return 
     * appropriate content.
     * @param object $tweet 
     * @return string
     */
    protected function selectTweetContent($tweet) {
        return (isset($tweet->retweeted_status)) ? 
            $tweet->retweeted_status->text : $tweet->text;
    }

    /**
     * Description
     * @param type $tweets 
     * @return type
     */
    protected function extractData($tweets) {
        $data = array();

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

            array_push($data, $data_item);
        }

        return $data;
    }
}
