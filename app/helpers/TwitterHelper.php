<?php
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

function replaceUrls($text, $entity) {
    $manipulated_string = '';

    foreach($entity as $item) {
        $manipulated_string = str_replace(
            $item->url, 
            '<a href="'.$item->expanded_url.'">'.$item->display_url.'</a>', 
            $text
        );
    }

    return $manipulated_string;
}

function replaceHashtags($text, $entity) {
    $manipulated_string = '';

    foreach($entity as $item) {
        $manipulated_string = str_replace(
            '#'.$item->text, 
            '<a href="http://twitter.com/search?q=%23'.$item->text.'&src=hash">#'.$item->text.'</a>', 
            $text
        );
    }

    return $manipulated_string;
}

function replaceUserMentions($text, $entity) {
    $manipulated_string = '';

    foreach($entity as $item) {
        $manipulated_string = str_replace(
            '@'.$item->screen_name,
            '<a href="http://twitter.com/'.$item->screen_name.'">@'.$item->screen_name.'</a>',
            $text
        );
    }

    return $manipulated_string;
}

function replaceMedias($text, $entity) {
    $manipulated_string = '';

    foreach($entity as $item) {
        $manipulated_string = str_replace(
            $item->url,
            '<a href="'.$item->media_url.'">'.$item->display_url.'</a>',
            $text
        );
    }

    return $manipulated_string;
}
?>