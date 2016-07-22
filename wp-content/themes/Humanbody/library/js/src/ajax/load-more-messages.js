jQuery(document).ready(function() {

    $('.load-more.messages').on({
        click: function() {
            var rid = $(this).attr('data-r');
            var startfrom = $(this).attr('data-s');
            load_more_messages(rid, startfrom);
        }
    })
    function load_more_messages(rid, startfrom) {
        spinal_ajax({
            data: {
                action: 'moremessages',
                rid: rid,
                start: startfrom
            },
            success: function(data) {
                var loadmore_button = $('.load-more.messages');
                $('.conversation-list ul').append(data);
                loadmore_button.attr('data-s', parseInt(loadmore_button.attr('data-s'))+5)
            
            },
            fail: function(status, error) {

            }
        })
    }


    //load more messages, client side on profile notifications page
    size_li = $("#table-profile-messages tr").size();
    x = $('#load-messages-profile').data('perpage');

    $('#load-messages-profile').on({
        click: function() {
            
            x= (x+1 <= size_li) ? x+1 : size_li;
            $('#table-profile-messages tr:lt('+x+')').show();

            if (x==size_li){
                $(this).hide();
            }

            return false;
        }
    })


})
