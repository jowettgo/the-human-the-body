jQuery(document).ready(function() {
    /* init the function for comments ajax */
    load_more_posts_category();
})
/**
 * loads the user comments via ajax
 * @method load_comments_profile
 * @return {string}           returns a string containing rows of comments
 */
function load_more_posts_category() {
    /* button */
    var selector = $('.category-load-more .load-more');
    /* table to update to */
    var update = $('.load-articles-ajax');
    /* starting page number */
    selector.data('page', 1);
    /* event binders */
    selector.on({
        click: function(e) {
            /* prevent default behavior on click*/
            e.preventDefault();
            var category = $(this).attr('data-c');
            /* get the page numeber inside the button data and increment it */
            var page = selector.data('page');
            page += 1
            spinal_ajax({
                /* send the ajax call */
                data: {
                    action: 'loadmoreposts',
                    p: page,
                    c:category
                },
                /* on ajax success */
                success: function(data) {

                    /* remove the button if the data is 0 */
                    if(data == '0' || data == '' || data == undefined) {
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
