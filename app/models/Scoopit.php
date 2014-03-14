<?php

class Scoopit {
	const SCOOPIT = 'scoopit';

    private $config;

	private $api_key;
	private $api_secret;

    public function __construct() {
        $this->getConfig();
        $consumer = new \ZendOAuth\Consumer($this->config);
        var_dump($consumer);die;

        $token = $consumer->getRequestToken();
        var_dump($token);die;
    }

    protected function getConfig() {
        $api_info = ORM::for_table('sm_accounts')
            ->where('account', self::SCOOPIT)
            ->find_one()
            ->as_array();

        $this->config = array(
            'callbackUrl' => 'http://127.0.0.1/sm-dash/public',
            'siteUrl' => 'http://www.scoop.it/oauth/request',
            'consumerKey' => $api_info['api_key'],
            'consumerSecret' => $api_info['api_secret']
        );
    }
}
?>