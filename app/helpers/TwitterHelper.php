<?php
/**
 * Twig_Extension_TwitterHelper class
 * @package helpers
 * @uses Twig_Extension
 * @author Mahendra Rai
 */

class Twig_Extension_TwitterHelper extends Twig_Extension {
    public function getFunctions() {
        return array(
            new Twig_SimpleFunction('display_tweet', 'displayTweet')
        );
    }

    public function getName() {
        return 'twitter_helper';
    }
}

/**
 * Replace entities in the tweet with url and display it.
 * @param string $text 
 * @param type $entities 
 * @return string
 */
function displayTweet($text, $entities) {
    $text = $text;

    foreach($entities as $key=>$entity) {
        if(((strcasecmp($key, 'urls')) == 0) && (sizeof($entity) > 0)) {
            $text = replaceUrls($text, $entity);
        }
        else if(((strcasecmp($key, 'hashtags')) == 0) && (sizeof($entity) > 0)) {
            $text = replaceHashtags($text, $entity);
        }
        else if(((strcasecmp($key, 'user_mentions')) == 0) && (sizeof($entity) > 0)) {
            $text = replaceUserMentions($text, $entity);
        }
        else if(((strcasecmp($key, 'media')) == 0) && (sizeof($entity) > 0)) {
            $text = replaceMedias($text, $entity);
        }
    }

    echo '<p>'.$text.'</p>';
}

/**
 * Replace url entities.
 * @param string $text 
 * @param type $entity 
 * @return string
 */
function replaceUrls($text, $entity) {
    $manipulated_string = $text;

    foreach($entity as $item) {
        $manipulated_string = str_ireplace(
            $item->url, 
            '<a href="'.$item->expanded_url.'" target="_blank">'.$item->display_url.'</a>', 
            $manipulated_string
        );
    }

    return $manipulated_string;
}

/**
 * Replace hashtag entities.
 * @param string $text 
 * @param type $entity 
 * @return string
 */
function replaceHashtags($text, $entity) {
    $manipulated_string = $text;

    foreach($entity as $item) {
        $manipulated_string = str_ireplace(
            '#'.$item->text, 
            '<a href="http://twitter.com/search?q=%23'.$item->text.'&src=hash" target="_blank">#'.$item->text.'</a>', 
            $manipulated_string
        );
    }

    return $manipulated_string;
}

/**
 * Replace usermention entities.
 * @param string $text 
 * @param type $entity 
 * @return string
 */
function replaceUserMentions($text, $entity) {
    $manipulated_string = $text;

    foreach($entity as $item) {
        $manipulated_string = str_ireplace(
            '@'.$item->screen_name,
            '<a href="http://twitter.com/'.$item->screen_name.'" target="_blank">@'.$item->screen_name.'</a>',
            $text
        );

        $text = $manipulated_string;
    }

    return $manipulated_string;
}

/**
 * Replace media entities.
 * @param string $text 
 * @param type $entity 
 * @return string
 */
function replaceMedias($text, $entity) {
    $manipulated_string = $text;

    foreach($entity as $item) {
        $manipulated_string = str_ireplace(
            $item->url,
            '<a id="image-view" value="'.$item->media_url.'" data-toggle="modal" data-target="#image-viewer">'.$item->display_url.'</a>',
            $manipulated_string
        );

        $manipulated_string .= '<br><img class="img-rounded" src="'.$item->media_url.'"/>';
    }

    return $manipulated_string;
}
?>