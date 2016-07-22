/*
* dinamic filler on boxes
 */

/* addaction on window ready */
jQuery(document).ready(function() {
    var parentboxes = $('.community-subpage .category-wrapper-outer');
    var totalchildren = parentboxes.children('li').length;
    parentboxes.data('total', totalchildren);
    fillers();
});
/* add action on resize */
jQuery(window).resize(function() {
    fillers();
});

/**
 * fillers add fillers to empty spots on the box grid
 * @method fillers
 * @return {[type]} [description]
 */
function fillers() {
    var window_w = $(document).width();
    /* parent of boxes */
    var parentboxes = $('.community-subpage .category-wrapper-outer');
    /* remove all the fillers */
    parentboxes.children('.empty.filler').remove();
    /* filler template */
    var filler =
    '<li class="category-list-items empty filler"><div class="category-wrapper-inner">' +
            '<div class="forum-category-inner">'+
                '<a href="#">'+
                    '<span>'+
                    '</span>'+
                '</a>'+
            '</div>'+
        '</div>'+
    '</li>';

    var emptyfiller = $('.empty.filler');
    var total = parentboxes.data('total');
    /* 4 columns */
    if(window_w > 973) {
        var columns = 4;
    }
    /* 3 columns */
    else {
        if(window_w > 763) {
            var columns = 3;
        }
        if(window_w < 764) {
            var columns = 2;
            if(window_w < 504) {
                var columns = 1;
            }

        }

    }
    /* calculate needed fillers */
    var empty = needed(total, columns);
    /* only append if the difference is greater than 0 */
    if(empty > 0) {
        for (var i = 0; i < empty; i++) {
            parentboxes.append(filler);
        }
    }
}
/**
 * needed calculate needed items to fill a row based on a total of items and columns, more like filling a numerical matrix
 * @method needed
 * @param  {int} total    number of total items
 * @param  {int} columns  number of columns
 * @return {int}         return needed items to fill the blank
 */
function needed(total, columns) {
    var rows =  Math.ceil(total/columns);
    var dif = (rows * columns) - total;
    return dif;
}
