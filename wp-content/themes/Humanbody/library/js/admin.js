jQuery(window).load(function() {
    var j = jQuery;
    /* filter meta on galery post type */

    if(j("#select_ input:checked").val() == 'photo') {
        j('#photo_').show();
        j('#video_').hide();
    }
    else {
        j('#video_').show();
        j('#photo_').hide();
    }

    j('#select_ input').on({
        click : function() {
            if(j(this).val() == 'photo') {
                j('#photo_').show();
                j('#video_').hide();
            }
            else {
                j('#video_').show();
                j('#photo_').hide();
            }
        }

    })

    /* admin white papers */
    if(j('#page_template').val() === 'page-white-papers.php') {
        j('#papers').show();
    }
    else {
        j('#papers').hide();
    }
    j('#page_template').on({
        change: function() {
            if(j(this).val() === 'page-white-papers.php') {
                j('#papers').show();
            }
            else {
                j('#papers').hide();
            }
        }
    })


    show_hide('#topic-type #cmb2-metabox-topic-type input[type="radio"]', 'meeting', '#topic-location');
})

/* cmb2 hide on value change */
function show_hide(inputpath, value, targetid) {
    var j = jQuery;
    if(j(inputpath+":checked").val() == value) {
        j(targetid).show();

    }
    else {
        j(targetid).hide();
    }

    j(inputpath).on({
        click : function() {
            if(j(this).val() == value) {
                j(targetid).show();

            }
            else {
                j(targetid).hide();
            }
        }

    })
}
