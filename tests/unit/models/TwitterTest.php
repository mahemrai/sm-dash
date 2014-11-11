<?php

require __DIR__ . "/../../../app/models/Twitter.php";

class TwitterTest extends \PHPUnit_Framework_TestCase {
    public function testSuccessfulDataExtraction() {
        $json_data = file_get_contents(__DIR__ . '/../../../tests/data/twitter1.json');
        $twitter = new Twitter(null);
        $data_array = $twitter->extractData(json_decode($json_data));

        $this->assertCount(1, $data_array);
        $this->assertEquals($data_array[0]['user'], 'sm-dash');
    }

    public function testEmptyData() {
        $json_data = file_get_contents(__DIR__ . '/../../../tests/data/twitter2.json');
        $twitter = new Twitter(null);
        $data_array = $twitter->extractData(json_decode($json_data));

        $this->assertNull($data_array);
    }

    public function testRetweetStatusDoesNotExist() {
        $json_data = file_get_contents(__DIR__ . '/../../../tests/data/twitter1.json');
        $array = json_decode($json_data);
        $twitter = new Twitter(null);
        $retweet_status = $twitter->selectTweetContent($array[0]);

        $this->assertTrue(isset($retweet_status));
        $this->assertEquals($retweet_status, 'Hello this is a test');
    }

    public function testRetweetStatusExists() {
        $json_data = file_get_contents(__DIR__ . '/../../../tests/data/twitter3.json');
        $array = json_decode($json_data);
        $twitter = new Twitter(null);
        $retweet_status = $twitter->selectTweetContent($array[0]);

        $this->assertTrue(isset($retweet_status));
        $this->assertEquals($retweet_status, 'Check this out');
    }
}