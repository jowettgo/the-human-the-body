jQuery(document).ready(function() {
    /* init the function for comments ajax */
    load_comments_profile();
    /* init the function for posts ajax on user profile */
    load_posts_profile();
    /* init the function for images ajax on user profile */
    load_images_profile();

    load_posts_ideas_profile();

    notification();

})



/**
 * loads the user comments via ajax
 * @method load_comments_profile
 * @return {string}           returns a string containing rows of comments
 */
function load_comments_profile() {
    /* button */
    var selector = $('.table-section.comments .load-more');
    /* table to update to */
    var update = $('.table-section.comments table');
    /* starting page number */
    selector.data('page', 1);
    /* event binders */
    selector.on({
        click: function(e) {
            /* prevent default behavior on click*/
            e.preventDefault();
            /* USER ID */
            var u_id = $(this).attr('data-u');
            /* get the page numeber inside the button data and increment it */
            var page = selector.data('page');
            page += 1
            spinal_ajax({
                /* send the ajax call */
                data: {
                    action: 'getcomments',
                    p: page,
                    i: u_id
                },
                /* on ajax success */
                success: function(data) {

                    /* remove the button if the data is 0 */
                    if(data == '0') {
                        selector.remove()
                    }
                    else {
                        /* append the returned rows */
                        update.append(data);
                        /* store the page number inside the element data */
                        selector.data('page', page);
                    }
                },
                /* do something on fail */
                fail: function(status, error) {

                }
            })

        }
    })
}

/**
 * loads the user posts via ajax on user profile
 * @method load_posts_profile
 * @return {string}           returns a string containing rows of posts
 */
function load_posts_profile() {
    /* button */
    var selector = $('.table-section.posts .load-more');
    /* table to update to */
    var update = $('.table-section.posts table');
    /* starting page number */
    selector.data('page', 1);
    /* event binders */
    selector.on({
        click: function(e) {
            /* prevent default behavior on click*/
            e.preventDefault();
            /* USER ID */
            var u_id = $(this).attr('data-u');
            /* get the page numeber inside the button data and increment it */
            var page = selector.data('page');
            page += 1
            spinal_ajax({
                /* send the ajax call */
                data: {
                    action: 'getUserPosts',
                    p: page,
                    i: u_id
                },
                /* on ajax success */
                success: function(data) {
                    /* remove the button if the data is 0 */
                    if(data == '0') {
                        selector.remove()
                    }
                    else {
                        /* append the returned rows */
                        update.append(data);
                        /* store the page number inside the element data */
                        selector.data('page', page);
                    }
                },
                /* do something on fail */
                fail: function(status, error) {

                }
            })

        }
    })
}



/**
 * loads the user posts ideas via ajax on user profile
 * @method load_posts_ideas_profile
 * @return {string}           returns a string containing rows of posts
 */
function load_posts_ideas_profile() {
    
    /* button */
    var selector = $('.table-section.ideas .load-more');
    /* table to update to */
    var update = $('.table-section.ideas table');
    /* starting page number */
    selector.data('page', 1);
    /* event binders */
    selector.on({
        click: function(e) {
            /* prevent default behavior on click*/
            e.preventDefault();
            /* USER ID */
            var u_id = $(this).attr('data-u');
            /* get the page numeber inside the button data and increment it */
            var page = selector.data('page');
            page += 1
            spinal_ajax({
                /* send the ajax call */
                data: {
                    action: 'getUserPostsIdeas',
                    p: page,
                    i: u_id,
                },
                /* on ajax success */
                success: function(data) {
                    /* remove the button if the data is 0 */
                    if(data == '0') {
                        selector.remove()
                    }
                    else {
                        /* append the returned rows */
                        update.append(data);
                        /* store the page number inside the element data */
                        selector.data('page', page);
                    }
                },
                /* do something on fail */
                fail: function(status, error) {

                }
            })

        }
    })
}


/**
 * loads the user posts via ajax on user profile
 * @method load_posts_profile
 * @return {string}           returns a string containing rows of posts
 */
function load_images_profile() {
    /* button */
    var selector = $('.table-section.pictures .load-more');
    /* table to update to */
    var update = $('.table-section.pictures table');
    /* starting page number */
    selector.data('page', 1);
    /* event binders */
    selector.on({
        click: function(e) {
            /* prevent default behavior on click*/
            e.preventDefault();
            /* USER ID */
            var u_id = $(this).attr('data-u');
            /* get the page numeber inside the button data and increment it */
            var page = selector.data('page');
            page += 1
            spinal_ajax({
                /* send the ajax call */
                data: {
                    action: 'getUserImages',
                    p: page,
                    i: u_id
                },
                /* on ajax success */
                success: function(data) {
                    /* remove the button if the data is 0 */
                    if(data == '0') {
                        selector.remove()
                    }
                    else {
                        /* append the returned rows */
                        update.append(data);
                        /* store the page number inside the element data */
                        selector.data('page', page);
                    }
                },
                /* do something on fail */
                fail: function(status, error) {

                }
            })

        }
    })
}

function notification() {

	$(".table-section.notifications-table .title-wrapper form label").click(function(e){

		var notification_value = $(this).next();
		var hide_element = $('.table-section.notifications-table table tr.unread');

		if ($(notification_value).is(':checked')){
			$(notification_value).val('2');
			$(notification_value).prop('checked',false);
		}else{
			$(notification_value).prop('checked',true)
			$(notification_value).val('1');
		}

		spinal_ajax({
                /* send the ajax call */
                data: {
                    action: 'getUserNotification',
                    val : notification_value.val()
                },
                /* on ajax success */
                success: function(data) {
                	console.log(data);
                    /* remove the button if the data is 0 */
                    if(notification_value.val == '2') {
                    	console.log(notification_value.val());
                        hide_element.hide()
                    }
                    else {
                    	console.log(notification_value.val());
                    	hide_element.show();
                    }
                },
                /* do something on fail */
                fail: function(status, error) {

                }
            })
	e.preventDefault();
});
}

