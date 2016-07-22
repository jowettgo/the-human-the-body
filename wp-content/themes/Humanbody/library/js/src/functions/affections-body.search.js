jQuery(document).ready(function() {
    /* search functionality for forum search */

    /* search input */
    var searchinput = $('#search-affection');
    /* parent element */
    var elementParent = $('.afectiuni-slideshow .slick-track');


    /* add a key up event binder for the search input */
    searchinput.on({
        keyup: function() {
            /* search string */
            var search = searchinput.val().toLowerCase().replace(/[^a-zA-Z0-9]/g,'');
                var list;
                /* get the children */
                var children = elementParent.children();
                /* do a loop through each children and do the search */
                children.each(function() {
                    /* current child */
                    var child = $(this);
                    /* hide elements */

                    /* strip special chars in string, transform to lower case and do a search */
                    var found = $(this).text().replace(/[^a-zA-Z0-9]/g,'').toLowerCase().search(search);

                    /* check if found child string position of search is not false and is greater than -1 (no position) */
                    if(found !== false && found > -1) {
                            /* unhide element */
                            var indexfound = $(this).children('.slide-inner').attr('data-index');
                            list = [indexfound];
                    }
                });
                //console.log(list);
                if(list && list.length == 1) {
                    console.log(list[0]);
                    $('.afectiuni-slideshow').slick('slickGoTo', list[0], false);
                }

        }
    })

})
