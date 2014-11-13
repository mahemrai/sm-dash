<?php
/**
 * Scoopit class
 * @package models
 * @author Mahendra Rai
 */
class Scoopit {
    const SCOOPIT = 'scoopit';

    private $topic;
    private $config;

    //constructor
    public function __construct($api_config) {
        $this->config = $this->getConfig($api_config);
        $this->topic = $this->config['defaultTopic'];
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

        return $this->extractPosts($scoops->posts);
    }

    /**
     * Send request to Scoop.it using Oauth Client and retrieve data 
     * for the selected topic. The data received will contain general 
     * information for the topic including posts and stats.
     * @param int $id 
     * @return string
     */
    public function getTopic($id=false) {
        $token = unserialize($_SESSION['SCOOPIT_ACCESS_TOKEN']);

        $client = $token->getHttpClient($this->config);

        ($id) ? $client->setUri("http://www.scoop.it/api/1/topic?id=".$id."&ncomments=0") : 
                $client->setUri("http://www.scoop.it/api/1/topic?urlName=".$this->topic."&ncomments=0");

        $client->setMethod(Zend\Http\Request::METHOD_GET);

        $response = $client->send();
        $topic = json_decode($response->getBody());

        $data = array(
            'id' => $topic->topic->id,
            'image' => $topic->topic->mediumImageUrl,
            'description' => $topic->topic->description,
            'name' => $topic->topic->name,
            'score' => $topic->topic->score,
            'posts' => $this->extractPosts($topic->topic->curatedPosts),
            'stats' => $topic->topic->stats
        );

        return $data;
    }

    /**
     * Send request to Scoop.it using Oauth Client and retrieve list of 
     * topics followed by the user.
     * @return string
     */
    public function getUserTopics() {
        $token = unserialize($_SESSION['SCOOPIT_ACCESS_TOKEN']);

        $client = $token->getHttpClient($this->config);
        $client->setUri(
            "http://www.scoop.it/api/1/profile?shortName=mahendra-rai&getStats=false&getCuratedTopics=true&getTags=false&getFollowedTopics=true"
        );
        $client->setMethod(Zend\Http\Request::METHOD_GET);
        
        $response = $client->send();
        $topics = json_decode($response->getBody());
        
        $data = array(
            'user_topics' => $this->extractTopics($topics->user->curatedTopics),
            'followed_topics' => $this->extractTopics($topics->user->followedTopics)
        );

        return $data;
    }

    /**
     * Construct api configuration from the data retrieved from YAML config file.
     * @return array
     */
    private function getConfig($api_config) {
        return array(
            'callbackUrl' => $api_config['callback_url'],
            'siteUrl' => 'http://www.scoop.it/oauth',
            'requestTokenUrl' => 'http://www.scoop.it/oauth/request',
            'accessTokenUrl' => 'http://www.scoop.it/oauth/access',
            'authorizeUrl' => 'http://www.scoop.it/oauth/authorize',
            'consumerKey' => $api_config['api_key'],
            'consumerSecret' => $api_config['api_secret'],
            'defaultTopic' => $api_config['default_topic']
        );
    }

    /**
     * Extract posts from the json response received from Scoop.it.
     * @param object $posts 
     * @return array | null
     */
    public function extractPosts($posts) {
        if (!empty($posts)) {
            foreach($posts as $post) {
                $data_item = array(
                    'reactionsCount' => $post->reactionsCount,
                    'commentsCount' => $post->commentsCount,
                    'title' => $post->title,
                    'image' => (isset($post->mediumImageUrl)) ? $post->mediumImageUrl : null,
                    'publicationDate' => date('Y-m-d', $post->publicationDate),
                    'url' => (isset($post->url)) ? $post->url : null,
                    'thanksCount' => $post->thanksCount
                );

                $data[] = $data_item;
            }

            return $data;
        }

        return null;
    }

    /**
     * Extract topics from the json response received from Scoop.it.
     * @param object $topics 
     * @return array | null
     */
    public function extractTopics($topics) {
        if (!empty($topics)) {
            foreach($topics as $topic) {
                $data_item = array(
                    'id' => $topic->id,
                    'name' => $topic->name,
                    'short_name' => $topic->shortName
                );

                $data[] = $data_item;
            }

            return $data;
        }

        return null;
    }
}
?>