jQuery(document).ready(function() {
    /* search functionality for forum search */

    /* search input */
    var searchinput = $('#search-ideas');
    /* parent element */
    var elementParent = $('.ideas-section');


    /* add a key up event binder for the search input */
    searchinput.on({
        keyup: function() {
            /* search string */
            var search = searchinput.val().toLowerCase().replace(/[^a-zA-Z0-9]/g,'');

                /* get the children */
                var children = elementParent.children('.col-20-percent');
                /* do a loop through each children and do the search */
                children.each(function() {
                    /* current child */
                    var child = $(this);
                    /* hide elements */
                    child.addClass('hidden');

                    /* strip special chars in string, transform to lower case and do a search */
                    var found = $(this).text().replace(/[^a-zA-Z0-9]/g,'').toLowerCase().search(search);
                    /* check if found child string position of search is not false and is greater than -1 (no position) */
                    if(found !== false && found > -1) {
                            /* unhide element */
                            $(this).removeClass('hidden');
                    }
                });

                /* count visible children */
                var totalchildren = elementParent.children('.col-20-percent').not('.hidden, .empty.filler').length;

                /* store the total count of children inside the parents data*/
                elementParent.data('total', totalchildren);
                /* apply filters */
                ideafillers();

        }
    })

})
