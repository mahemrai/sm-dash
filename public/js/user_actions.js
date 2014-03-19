/**
 * Script to handle all the user actions such as favoriting a tweet or viewing  
 * an image. Performs ajax request to appropriate route depending on the action 
 * carried out by the user.
 */

$(document).ready(function() {
    //send ajax request to perform retweet action when user 
    //clicks on retweet button
    $('a.retweet').click(function() {
        //get id of the tweet from value attribute of the button
        var tweet_id = $(this).attr('value');

        var url = '/twitter/retweet';
        var json = { id: tweet_id };
        var elem = 'a#retweet-'+tweet_id;
        var replacementHtml = '<span>Retweeted</span>'

        performPostRequest(url, json, elem, replacementHtml);
    });

    //send ajax request to perform favorite action when user 
    //clicks on favorite button
    $('a.favorite').click(function() {
        //get id of the tweet from value attribute of the button
        var tweet_id = $(this).attr('value');

        //perform ajax request by attaching post data
        $.post("/twitter/favorite", { id: tweet_id })
            .done(function(data) {
                var response = JSON.parse(data);

                //show the text if the request was performed successfully otherwise 
                //display a notification
                if(response.result) {
                    $('a#favorite-'+tweet_id).replaceWith('<span>Favorited</span>');
                }
                else {
                    alert('Could not complete the request. Try again.');
                }
            });
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
        
        $.post("/twitter/tweet", { status: text })
            .done(function(data) {
                var response = JSON.parse(data);

                if(response.result) {
                    alert('Tweet successful');
                }
                else {
                    alert('Could not complete the request. Try again.');
                }
            });
    });

    //send ajax request to perform delete action when user clicks on 
    //delete button to delete the tweet
    $('a.delete').click(function() {
        //get id of the tweet from the value attribute
        var tweet_id = $(this).attr('value');

        $.post("/twitter/delete", { id: tweet_id })
            .done(function(data) {
                var response = JSON.parse(data);

                if(response.result) {
                    $('a#delete-'+tweet_id).replaceWith('<span>Deleted</span>');
                }
                else {
                    alert('Could not complete the request. Try again.');
                }
            });
    });

    $('a.load-topic').click(function() {
        var topic_id = $(this).attr('value');

        $.get("/scoopit/topic/"+topic_id, function(data) {
            var response = JSON.parse(data);
            redrawTopicInfo(response);
        });
    });
});

function performPostRequest(url, json, elem, replacementHtml) {
    $.post(url, json)
        .done(function(data) {
            var response = JSON.parse(data);

            if(response.result) {
                $(elem).replaceWith(replacementHtml);
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