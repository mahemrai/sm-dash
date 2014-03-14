<?php
/**
 * Scoopit class
 * @package models
 * @author Mahendra Rai
 */
class Scoopit {
    const SCOOPIT = 'scoopit';

    private $config;
    private $api_key;
    private $api_secret;

    //constructor
    public function __construct() {
        $this->getConfig();
    }

    public function authorise() {
        $consumer = new \ZendOAuth\Consumer($this->config);
        $token = $consumer->getRequestToken();
        $_SESSION['SCOOPIT_REQUEST_TOKEN'] = serialize($token);
        $consumer->redirect();
    }

    public function access() {
        $consumer = new \ZendOAuth\Consumer($this->config);
        $token = $consumer->getAccessToken($_GET, unserialize($_SESSION['SCOOPIT_REQUEST_TOKEN']));
        $_SESSION['SCOOPIT_ACCESS_TOKEN'] = serialize($token);
        $_SESSION['SCOOPIT_REQUEST_TOKEN'] = null;
    }

    public function getCompilation($count, $include_tags) {
        $token = unserialize($_SESSION['SCOOPIT_ACCESS_TOKEN']);

        $client = $token->getHttpClient($this->config);
        $client->setUri("http://www.scoop.it/api/1/compilation?count=$count&ncomments=0&getTags=$include_tags&getTagsForTopic=$include_tags");
        $client->setMethod(Zend\Http\Request::METHOD_GET);

        $response = $client->send();
        return $response->getBody();
    }

    protected function getConfig() {
        $api_info = ORM::for_table('sm_accounts')
            ->where('account', self::SCOOPIT)
            ->find_one()
            ->as_array();

        $this->config = array(
            'callbackUrl' => 'http://127.0.0.1/sm-dash/public/index.php/scoopit/authenticate',
            'requestTokenUrl' => 'http://www.scoop.it/oauth/request',
            'accessTokenUrl' => 'http://www.scoop.it/oauth/access',
            'authorizeUrl' => 'http://www.scoop.it/oauth/authorize',
            'consumerKey' => $api_info['api_key'],
            'consumerSecret' => $api_info['api_secret']
        );
    }
}
?>