<?php

require __DIR__ . "/../../../app/models/Scoopit.php";

/**
 * ScoopitTest
 * @package tests
 * @uses PHPUnit_Framework_TestCase
 * @author Mahendra Rai
 */
class ScoopitTest extends \PHPUnit_Framework_TestCase {
    /**
     * Test extracting of topic from Scoopit response.
     */
    public function testSuccessfulTopicExtraction() {
        $json_data = file_get_contents(__DIR__ . '/../../../tests/data/scoopit_topics.json');
        $scoopit = new Scoopit(null);
        $data_array = $scoopit->extractTopics(json_decode($json_data));

        $this->assertCount(1, $data_array);
        $this->assertEquals(41123, $data_array[0]['id']);
    }

    /**
     * Test handling of empty response.
     */
    public function testEmptyTopicData() {
        $json_data = file_get_contents(__DIR__ . '/../../../tests/data/empty.json');
        $scoopit = new Scoopit(null);
        $data_array = $scoopit->extractTopics(json_decode($json_data));

        $this->assertNull($data_array);
    }

    /**
     * Test extraction of posts from Scoopit response.
     */
    public function testSuccessfulPostExtraction() {
        $json_data = file_get_contents(__DIR__ . '/../../../tests/data/scoopit_posts.json');
        $scoopit = new Scoopit(null);
        $data_array = $scoopit->extractPosts(json_decode($json_data));

        $this->assertCount(2, $data_array);
        $this->assertEquals(6, (int) $data_array[0]['commentsCount']);
        $this->assertEquals('Topic 2', $data_array[1]['title']);
    }

    /**
     * Test handling of empty response.
     */
    public function testEmptyPostData() {
        $json_data = file_get_contents(__DIR__ . '/../../../tests/data/empty.json');
        $scoopit = new Scoopit(null);
        $data_array = $scoopit->extractPosts(json_decode($json_data));

        $this->assertNull($data_array);
    }
}