jQuery(document).ready(function() {
    /* wordpress css class center align images */
    $('.aligncenter').parent().addClass('wp-center');

    /* filter users on the map */
    $('.map-search-affections').show();
    $('.search-profile').show();
    $('#main-affections-map, #main-interest, #interest-categories,#interest-types').change(function() {

        var first_interests = $('#main-interest').val();
        var second_interests = $('#interest-categories').val();
        var third_interests = $('#interest-types').val();
        spinal_ajax({
            data: {
                action: 'filter_users_cities',
                alist: $('#main-affections-map').val(),
                first_interests: first_interests,
                second_interests: second_interests,
                third_interests: third_interests
            },
            success: function(data) {
                // console.log(data);
                var valid = data;
                $('.amcharts-map-area').css({opacity: 1});
                $('.amcharts-map-area').not(valid).css({opacity: 0.1});
            },
            fail: function(status, error) {

            }
        })
    })
    /* end filter users on the map */


    /* add a popup are you sure you want to remove this friend/message */
   $('.are-you-sure').fancybox();
   $('.delete-conversation').unbind('click')
   $('.delete-conversation').on({
       click: function(e) {
           e.preventDefault();
           var link = $(this).attr('href');
           $('.are-you-sure').trigger('click');
           $(document).on('click', '.remove-yes', function() {
               var rid = $('.delete-conversation').attr('data-rid');
               remove_conversation(rid);
           })
           $(document).on('click', '.remove-no', function() {
               $.fancybox.close();
           })
       }
   })
   function remove_conversation(rid) {
        spinal_ajax({
            data: {
                action: 'removeconversation',
                rid: rid
            },
            success: function(data) {
                $('.conversation-list').fadeOut(400, function() {
                    window.location.href = $('.delete-conversation').attr('data-redirect');
                });
            },
            fail: function(status, error) {

            }
        })
    }


    $('.remove-friend').on({
        click: function(e) {
            e.preventDefault();
            var link = $(this).attr('href');
            $('.are-you-sure').trigger('click');
            $(document).on('click', '.remove-yes', function() {
                window.location.href= link;
            })
            $(document).on('click', '.remove-no', function() {
                $.fancybox.close();
            })
        }
    })
})

/* patch on search js */
jQuery(document).ready(function() {
    /* search functionality for forum search */

    /* search input */
    var searchinput = $('#search-affection');
    /* unbind old functionality */
    searchinput.unbind('keyup');
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
                var children2 = elementParent.children().not('.slick-cloned');
                /* do a loop through each children and do the search */

				var total_slides = children2.length;
                children.each(function() {
                    /* current child */
                    var child = $(this);
                    /* hide elements */

                    /* strip special chars in string, transform to lower case and do a search */
                    var found = $(this).text().replace(/[^a-zA-Z0-9]/g,'').toLowerCase().search(search);

					$('#number').html(found);
                    /* check if found child string position of search is not false and is greater than -1 (no position) */
                    if(found !== false && found > -1) {
                            /* unhide element */
                            var indexfound = $(this).children('.slide-inner').attr('data-index');
                            list = [indexfound];
                            child.removeClass('darken');
                            child.addClass('lighten');

                    }
                    else {
                        child.addClass('darken')
                        child.removeClass('lighten');
                    }

                    var tots = total_slides - $('.darken').not('.slick-cloned').length;
                    if(searchinput.val().length >0){
                    	$('#number-of-items').show();
                    	if(tots == 1){
                    		$('#number-of-items').html('1 result found');
                    	}else{
                    		$('#number-of-items').html(tots+' results found');
                    	}
                	}else{
                		$('#number-of-items').hide();
                    }

                });
                    
                if(list && list.length == 1) {
                    $('.afectiuni-slideshow').slick('slickGoTo', list[0], false);
                }else{
                }

        }
    })



    /*############################*/
    $('.select2').select2();
    
})








