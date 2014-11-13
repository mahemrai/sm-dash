<?php

require __DIR__ . "/../../../app/models/Twitter.php";

/**
 * TwitterTest
 * @package tests
 * @uses PHPUnit_Framework_TestCase
 * @author Mahendra Rai
 */
class TwitterTest extends \PHPUnit_Framework_TestCase {
    protected static $data;

    /**
     * Setup test data by fetching tweet dataset from a file.
     */
    public static function setUpBeforeClass() {
        $json_data = file_get_contents(__DIR__ . '/../../../tests/data/tweets.json');
        self::$data = json_decode($json_data);
    }

    /**
     * Test extraction of tweets from Twitter response.
     */
    public function testSuccessfulDataExtraction() {
        $twitter = new Twitter(null);
        $data_array = $twitter->extractData(self::$data);

        $this->assertCount(2, $data_array);
        $this->assertEquals($data_array[0]['user'], 'sm-dash');
    }

    /**
     * Test handling of empty response.
     */
    public function testEmptyData() {
        $json_data = file_get_contents(__DIR__ . '/../../../tests/data/empty.json');
        $twitter = new Twitter(null);
        $data_array = $twitter->extractData(json_decode($json_data));

        $this->assertNull($data_array);
    }

    /**
     * Test for confirming that the received tweet data is not retweeted by the user.
     */
    public function testRetweetStatusDoesNotExist() {
        $twitter = new Twitter(null);
        $retweet_status = $twitter->selectTweetContent(self::$data[0]);

        $this->assertTrue(isset($retweet_status));
        $this->assertEquals($retweet_status, 'Hello this is a test');
    }

    /**
     * Test for confirming that the received tweet data is retweeted by the user.
     */
    public function testRetweetStatusExists() {
        $twitter = new Twitter(null);
        $retweet_status = $twitter->selectTweetContent(self::$data[1]);

        $this->assertTrue(isset($retweet_status));
        $this->assertEquals($retweet_status, 'Check this out');
    }
}