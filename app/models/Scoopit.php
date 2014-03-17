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
    private $data = array();

    //constructor
    public function __construct() {
        $this->getConfig();
    }

    /**
     * Perform Oauth process with Scoop.it using Zend's Oauth library and 
     * redirect user to callback page.
     */
    public function authorise() {
        $consumer = new \ZendOAuth\Consumer($this->config);
        //ask for request token
        $token = $consumer->getRequestToken();
        //store the request token into our session object
        $_SESSION['SCOOPIT_REQUEST_TOKEN'] = serialize($token);
        //redirect user to callback page to complete the process
        $consumer->redirect();
    }

    /**
     * Complete the authorisation process by obtaining the access token.
     */
    public function getAccessToken() {
        $consumer = new \ZendOAuth\Consumer($this->config);
        //ask for access token
        $token = $consumer->getAccessToken($_GET, unserialize($_SESSION['SCOOPIT_REQUEST_TOKEN']));
        //store the access token into our session object
        $_SESSION['SCOOPIT_ACCESS_TOKEN'] = serialize($token);
        //destroy the request token
        $_SESSION['SCOOPIT_REQUEST_TOKEN'] = null;
    }

    /**
     * Send request to Scoop.it using Oauth Client and retrieve mixture of contents 
     * from topics followed by the user.
     * @param int $count
     * @return string
     */
    public function getCompilation($count) {
        $token = unserialize($_SESSION['SCOOPIT_ACCESS_TOKEN']);

        $client = $token->getHttpClient($this->config);
        $client->setUri(
            "http://www.scoop.it/api/1/compilation?count=$count&ncomments=0&getTags=false&getTagsForTopic=false"
        );
        $client->setMethod(Zend\Http\Request::METHOD_GET);

        $response = $client->send();
        $scoops = json_decode($response->getBody());

        foreach($scoops->posts as $item) {
            $data_item = array(
                'reactionsCount' => $item->reactionsCount,
                'commentsCount' => $item->commentsCount,
                'title' => $item->title,
                'image' => (isset($item->mediumImageUrl)) ? $item->mediumImageUrl : null,
                'publicationDate' => date('Y-m-d', $item->publicationDate),
                'url' => $item->url,
                'thanksCount' => $item->thanksCount
            );

            array_push($this->data, $data_item);
        }

        return $this->data;
    }

    public function rescoopPost($post_id) {
        $token = unserialize($_SESSION['SCOOPIT_ACCESS_TOKEN']);

        $client = $token->getHttpClient($this->config);
        $client->setUri(
            "http://www.scoop.it/api/1/post"
        );
        $client->setMethod(Zend\Http\Request::METHOD_POST);
        $client->setParameterPost(array(
            'action' => 'rescoop',
            'id' => $post_id
        ));

        $response = $client->send();
    }

    /**
     * Retrieve api information from the database and set configuration data for 
     * the api client.
     * @return array
     */
    protected function getConfig() {
        $api_info = ORM::for_table('sm_accounts')
            ->where('account', self::SCOOPIT)
            ->find_one()
            ->as_array();

        $this->config = array(
            'callbackUrl' => 'http://localhost:8117/scoopit/authenticate',
            'siteUrl' => 'http://www.scoop.it/oauth',
            'requestTokenUrl' => 'http://www.scoop.it/oauth/request',
            'accessTokenUrl' => 'http://www.scoop.it/oauth/access',
            'authorizeUrl' => 'http://www.scoop.it/oauth/authorize',
            'consumerKey' => $api_info['api_key'],
            'consumerSecret' => $api_info['api_secret']
        );
    }
}
?>