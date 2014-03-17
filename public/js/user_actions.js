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

        //perform ajax request by attaching post data
        $.post("/retweet", { id: tweet_id })
            .done(function(data) {
                var response = JSON.parse(data);

                //show the text if the request was performed successfully otherwise
                //display a notification
                if(response.result) {
                    $('a#retweet-'+tweet_id).replaceWith('<span>Retweeted</span>');
                }
                else {
                    alert('Could not complete the request. Try again.');
                }
            });
    });

    //send ajax request to perform favorite action when user 
    //clicks on favorite button
    $('a.favorite').click(function() {
        //get id of the tweet from value attribute of the button
        var tweet_id = $(this).attr('value');

        //perform ajax request by attaching post data
        $.post("/favorite", { id: tweet_id })
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
});