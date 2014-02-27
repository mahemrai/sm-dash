<?php
/**
 * TwitterResponseManipulator class
 * @package default
 * @author Mahendra Rai
 */
class TwitterResponseManipulator {

    public static function processHashtags($entities) {
        foreach($entities->hashtags as $hashtag) {
        }
    }

    public static function processSymbols($entities) { }

    public static function processUrls($entities) { }

    public static function processUserMentions($entities) { }
}
?>