/**
 * Remove conversation in the message room with ajax
 * it removes all the listed messages and then redirects to main messages page
 */

jQuery(document).ready(function() {
    $('.delete-conversation').unbind('click')
    $('.delete-conversation').on({
        click : function(e) {
            e.preventDefault();
            var rid = $(this).attr('data-rid');
            remove_conversation(rid);
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
})
