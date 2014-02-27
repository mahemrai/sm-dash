<?php
/**
 * TwitterResponseManipulator class
 * @package default
 * @author Mahendra Rai
 */
class TwitterResponseManipulator {
    protected static $data = array();

    /**
     * Description
     * @param type $tweets 
     * @return type
     */
	public static function extract($tweets) {
        foreach($tweets as $tweet) {
        	$data_item = array(
        		'created_at' => $tweet->created_at,
        		'text' => utf8_encode($tweet->text),
        		'user' => $tweet->user,
                'retweet_count' => $tweet->retweet_count,
                'favorite_count' => $tweet->favorite_count,
                //'entities' => $tweet->entities
                'entities' => self::processEntities($tweet->entities)
            );

            array_push(self::$data, $data_item);
        }

        var_dump(self::$data);die;
	}

    /**
     * Description
     * @param type $entities 
     * @return type
     */
	protected static function processEntities($entities) {
        $entity_data = array();

        foreach($entities as $key=>$entity) {
        	switch($key) {
        		case 'hashtags':
        		    break;
        		case 'symbols':
        		    break;
        		case 'urls':
        		    break;
        		case 'user_mentions':
        		    break;
        	}
        }

        die;
	}
}
?>