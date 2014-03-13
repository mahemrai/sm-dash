<?php

/**
 * Twitter class
 * @package models
 * @author Mahendra Rai
 */
class Twitter {
    const TWITTER = 'Twitter';

    private $settings;
    private $data = array();
	
    //constructor
    public function __construct() {
        $this->getApiInfo();
    }

    /**
     * Get home line tweets from Twitter.
     * @return array
     */
    public function getHomeTimeline() {
        $url = 'https://api.twitter.com/1.1/statuses/home_timeline.json';
	    $request_method = 'GET';

        $client = new TwitterApiExchange($this->settings);
        $tweets = json_decode($client->buildOauth($url, $request_method)->performRequest());

        foreach($tweets as $tweet) {
            $data_item = array(
                'created_at' => $tweet->created_at,
                'text' => $this->selectTweetContent($tweet),
                'user' => $tweet->user,
                'retweet_count' => $tweet->retweet_count,
                'favorite_count' => $tweet->favorite_count,
                'entities' => $tweet->entities
            );

            array_push($this->data, $data_item);
        }
        
        return $this->data;
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

        foreach($tweets as $tweet) {
            $data_item = array(
                'created_at' => $tweet->created_at,
                'text' => $tweet->text,
                'user' => $tweet->user,
                'retweet_count' => $tweet->retweet_count,
                'favorite_count' => $tweet->favorite_count,
                'entities' => $tweet->entities
            );

            array_push($this->data, $data_item);
        }

        return $this->data;
    }

    /**
     * Description
     * @return type
     */
    public function postRetweet($tweet_id) {
        $url = 'https://api.twitter.com/1.1/statuses/retweet/'.$tweet_id.'.json';
        $request_method = 'POST';

        $client = new TwitterApiExchange($this->settings);
        $response = json_decode($client->buildOauth($url, $request_method)->performRequest());

        return $response;
    }

    public function postFavorite() {
        $url = 'https://api.twitter.com/1.1/statuses/favorite/';
        $request_method = 'POST';

        $client = new TwitterApiExchange($this->settings);
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
}
?>
