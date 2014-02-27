<?php
/**
 * Twitter class
 * @package models
 * @author Mahendra Rai
 */
class Twitter {
    const TWITTER = 'Twitter';

	private $settings;
	
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
        $response = json_decode($client->buildOauth($url, $request_method)->performRequest());

        return $response;
	}

    /**
     * Get user tweets from Twitter.
     * @return array
     */
	public function getUserTimeline() {
		$url = 'https://api.twitter.com/1.1/statuses/user_timeline.json';
		$request_method = 'GET';

		$client = new TwitterApiExchange($this->settings);
		$response = json_decode($client->buildOauth($url, $request_method)->performRequest());

        return $response;
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
}
?>