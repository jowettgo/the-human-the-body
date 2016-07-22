jQuery(document).ready(function() {
    /* init the function for comments ajax */
    contact_popup_send();
});

/**
 *
 * @method load_comments_profile
 * @return {string}           returns a string containing rows of comments
 */
function contact_popup_send () {

    /* event binders */
    jQuery(document).on('submit','#contact-popup form', function(e) {
            var form = $(this);

            e.preventDefault();

            var fullname = form.find('input[name="fullname"]').val();
            var email    = form.find('input[name="email"]').val();
            var message  = form.find('textarea[name="message"]').val();

            spinal_ajax({
                /* send the ajax call */
                data: {
                    action: 'send_mail',
                    fullname: fullname,
                    email: email,
                    message: message,
                    send: 'send_mail'
                },
                /* on ajax success */
                success: function(data) {

                    form.html(data);
                },
                /* do something on fail */
                fail: function(status, error) {

                }
            })

        })
}
