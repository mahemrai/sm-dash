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
        var replacementHtml = '<span class="label label-info">Retweeted</span>';

        $('i#retweet-'+tweet_id).replaceWith('<i class="fa fa-refresh fa-spin"></i>');

        sendPostRequest(url, json, elem, replacementHtml);
    });

    //send ajax request to perform favorite action when user 
    //clicks on favorite button
    $('a.favorite').click(function() {
        var tweet_id = $(this).attr('value');
        var url = '/twitter/favorite';
        var json = { id: tweet_id };
        var elem = 'a#favorite-'+tweet_id;
        var replacementHtml = '<span class="label label-warning">Favorited</span>';

        $('i#favorite-'+tweet_id).replaceWith('<i class="fa fa-refresh fa-spin"></i>');

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

        //$('i#post').replaceWith('<i id="post" class="fa fa-refresh fa-spin"></i>');
        $('#tweet-post').modal('show');

        sendPostRequest(url, json, null, null);
    });

    //send ajax request to perform delete action when user clicks on 
    //delete button to delete the tweet
    $('a.delete').click(function() {
        var tweet_id = $(this).attr('value');
        var url = '/twitter/delete';
        var json = { id: tweet_id };
        var elem = 'a#delete-'+tweet_id;
        var replacementHtml = '<span class="label label-warning">Deleted</span>';

        $('i#delete-'+tweet_id).replaceWith('<i class="fa fa-refresh fa-spin"></i>');

        sendPostRequest(url, json, elem, replacementHtml);
    });

    //send ajax request to perform topic loading action when user clicks 
    //or selects the topic to view
    $('button.load-topic').click(function() {
        var topic_id = $(this).attr('value');

        $('#topic-load').modal('show');

        $.get("/scoopit/topic/"+topic_id, function(data) {
            var response = JSON.parse(data);
            redrawTopicInfo(response);
            redrawPosts(response);
            $('#topic-load').modal('hide');
        });
    });

    $('a#account-info').click(function() {
        $('div.message').html('');

        $('#api-key').val('');
        $('#api-secret').val('');
        $('#oauth-token').val('');
        $('#oauth-secret').val('');

        var account = $(this).attr('value');

        $.get("/accounts/"+account, function(data) {
            var info = JSON.parse(data);

            $('#api-account').val(account);
            $('#api-key').val(info.data.api_key);
            $('#api-secret').val(info.data.api_secret);
            $('#oauth-token').val(info.data.oauth_token);
            $('#oauth-secret').val(info.data.oauth_token_secret);

            $('button.edit').attr('id', 'edit-details');
            $('form').attr('action', '/settings/edit');
        }).fail(function() {
            $('#api-account').val(account);
            $('button.edit').attr('id', 'new-details');
            $('form').attr('action', '/settings/create');

            $('div.message').html('<div class="alert alert-warning">API details does not exist.</div>');
        });
    });

    $('form').submit(function(e) {
        e.preventDefault();

        var json = {
            name: $('#api-account').attr('value'),
            api_key: $('#api-key').val(),
            api_secret: $('#api-secret').val(),
            oauth_token: $('#oauth-token').val(),
            oauth_token_secret: $('#oauth-secret').val()
        };

        $.post($('form').attr('action'), json)
            .done(function(data) {
                var response = JSON.parse(data);

                if(response.result) {
                    $('div.message').html('<div class="alert alert-success">API details successfully saved.</div>');
                }
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
                    //$('i#post').replaceWith('<i id="post" class="fa fa-pencil-square-o"></i>');
                    $('#tweet-post').modal('hide');
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
    $('#topic-score').text(response.result.score);
    $('#topic-uv').text(response.result.stats.uv);
    $('#topic-uvp').text(response.result.stats.uvp);
    $('#topic-v').text(response.result.stats.v);
    $('#topic-vp').text(response.result.stats.vp);
    $('#topic-followers').text(response.result.stats.followers);
}

/**
 * Replaces posts with posts related to the selected topic.
 */
function redrawPosts(response) {
    var html = '';
    for(var i=0; i<response.result.posts.length; i++) {
        var post = response.result.posts[i];

        html += '<div class="well"><div class="row" id="content"><div class="col-sm-12">';
        if(post['image']) {
            html += '<div class="col-sm-5"><img src="'+post['image']+'"/></div>';
            html += '<div class="col-sm-7"><span>'+post['title']+'</span></div></div></div>';
        }
        else html += '<div class="col-sm-12"><span>'+post['title']+'</div></div></div>';
        html += '<div class="row" id="footer"><div class="col-sm-6">';
        html += '<span>Reactions: '+post['reactionsCount']+' Comments: '+post['commentsCount']+' Thanks: '+post['thanksCount']+'</span></div>';
        html += '<div class="col-sm-6">';
        html += '<a href="'+post['url']+'" target="_blank" class="btn btn-primary btn-sm"><i class="fa fa-eye"></i> View</a></div>';
        html += '</div></div>';
    }

    $('#post-container').html(html);
}