/**
 * Script to handle all the user actions such as favoriting a tweet or viewing  
 * an image. Performs ajax request to appropriate route depending on the action 
 * carried out by the user.
 */

$(document).ready(function() {
    //send ajax request to perform retweet action when user 
    //clicks on retweet button
    $('a.retweet').click(function() {
        var tweet_id = $(this).attr('value');
        var url = '/twitter/retweet';
        var json = { id: tweet_id };
        var elem = 'a#retweet-'+tweet_id;
        var replacementHtml = '<span>Retweeted</span>';

        sendPostRequest(url, json, elem, replacementHtml);
    });

    //send ajax request to perform favorite action when user 
    //clicks on favorite button
    $('a.favorite').click(function() {
        var tweet_id = $(this).attr('value');
        var url = '/twitter/favorite';
        var json = { id: tweet_id };
        var elem = 'a#favorite-'+tweet_id;
        var replacementHtml = '<span>Favorited</span>';

        sendPostRequest(url, json, elem, replacementHtml);
    });

    //add image in body of the modal when it loads
    $('a#image-view').click(function() {
        var image_src = $(this).attr('value');

        $('.modal-body').html('<img src="'+image_src+'"/>');
    });

    $('li#menu').click(function() {    
    });

    //send ajax request to perform tweet action when user submits form 
    //to post a new tweet
    $('form#tweetForm').submit(function(event) {
        event.preventDefault();

        var text = $('textarea#status').val();
        var url = '/twitter/tweet';
        var json = { status: text };

        sendPostRequest(url, json, null, null);
    });

    //send ajax request to perform delete action when user clicks on 
    //delete button to delete the tweet
    $('a.delete').click(function() {
        var tweet_id = $(this).attr('value');
        var url = '/twitter/delete';
        var json = { id: tweet_id };
        var elem = 'a#delete-'+tweet_id;
        var replacementHtml = '<span>Deleted</span>';

        sendPostRequest(url, json, elem, replacementHtml);
    });

    //send ajax request to perform topic loading action when user clicks 
    //or selects the topic to view
    $('a.load-topic').click(function() {
        var topic_id = $(this).attr('value');

        $.get("/scoopit/topic/"+topic_id, function(data) {
            var response = JSON.parse(data);
            redrawTopicInfo(response);
            redrawPosts(response);
        });
    });

    $('a#edit-account').click(function() {
        var account_id = $(this).attr('value');

        $.get("/accounts/"+account_id, function(data) {
            var info = JSON.parse(data);

            $('#api-key').attr('value', info.data.api_key);
            $('#api-secret').attr('value', info.data.api_secret);
            $('#oauth-token').attr('value', info.data.oauth_token);
            $('#oauth-secret').attr('value', info.data.oauth_token_secret);
        });
    });
});

/**
 * Sends POST request to the server with json data and processes the 
 * response.
 */
function sendPostRequest(url, json, elem, replacementHtml) {
    $.post(url, json)
        .done(function(data) {
            var response = JSON.parse(data);

            if(response.result) {
                if(elem === null) {
                    alert('Tweet successful');
                }
                else $(elem).replaceWith(replacementHtml);
            }
            else alert('Could not complete the request. Try again.');
        })
}

/**
 * Replaces text of the select elements to display information about the selected 
 * topic.
 */
function redrawTopicInfo(response) {
    $('#topic-name').text(response.result.name);
    $('#topic-desc').text(response.result.description);
    $('#topic-score').text("Score: "+response.result.score);
    $('#topic-uv').text("Visitors: "+response.result.stats.uv);
    $('#topic-uvp').text("Visitors Progression: "+response.result.stats.uvp);
    $('#topic-v').text("Views: "+response.result.stats.v);
    $('#topic-vp').text("Views Progression: "+response.result.stats.vp);
    $('#topic-followers').text("Followers: "+response.result.stats.followers);
}

/**
 * Replaces posts with posts related to the selected topic.
 */
function redrawPosts(response) {
    var html = '';
    for(var i=0; i<response.result.posts.length; i++) {
        var post = response.result.posts[i];

        html += '<div class="well"><div class="row">';
        html += '<div class="col-sm-5"><img src="'+post['image']+'"/></div>';
        html += '<div class="col-sm-7"><span>'+post['title']+'</span></div></div>';
        html += '<p><span>Reactions: '+post['reactionsCount']+' Comments: '+post['commentsCount']+' Thanks: '+post['thanksCount']+'</span></p>';
        html += '<a href="'+post['url']+'" target="_blank" class="btn btn-primary btn-sm">View</a>';
        html += '</div>';
    }

    $('#post-container').html(html);
}