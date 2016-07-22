/*
* dinamic filler on boxes
 */

/* add action on window ready */
jQuery(document).ready(function() {
    var parentboxes = $('.ideas-section');
    var totalchildren = parentboxes.children('.col-20-percent').not('.filler').length;
    parentboxes.data('total', totalchildren);
    if(parentboxes.children().length > 0) {
        ideafillers();
    }
});
/* add action on resize */
jQuery(window).resize(function() {
    var parentboxes = $('.ideas-section');
    if(parentboxes.children().length > 0) {
        ideafillers();
    }
});

/**
 * fillers add fillers to empty spots on the box grid
 * @method fillers
 * @return {[type]} [description]
 */
function ideafillers() {
    var window_w = $(document).width();
    /* parent of boxes */
    var parentboxes = $('.ideas-section');
    if(parentboxes.data('filler')) {
        var filler = parentboxes.data('filler')
    }
    else {
        parentboxes.data('filler', parentboxes.children('.empty.filler')[0].outerHTML)
        var filler = parentboxes.data('filler')
    }

    /* remove all the fillers */
    parentboxes.children('.empty.filler').remove();


    var total = parentboxes.data('total');

    if(window_w > 1600) {
        /* 5 columns */
        var columns = 5;
    }
    else {
        /* 4 columns */
        if(window_w > 1200) {
            var columns = 4;
        }
        /* 3 columns */
        else {
            if(window_w > 990) {
                var columns = 3;
            }
            if(window_w < 991) {
                var columns = 2;
                if(window_w < 601) {
                    var columns = 1;
                }

            }

        }
    }

    /* calculate needed fillers */
    var empty = neededideas(total, columns);
        console.log(total)
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
function neededideas(total, columns) {
    var rows =  Math.ceil(total/columns);
    var dif = (rows * columns) - total;
    return dif;
}
