jQuery(document).ready(function() {
    /* init the function for comments ajax */
    signin_popup_signin();
});

/**
 *
 * @method load_comments_profile
 * @return {string}           returns a string containing rows of comments
 */
function signin_popup_signin () {

    /* event binders */
    jQuery(document).on('submit','.sign-in-ajax', function(e) {
            var form = $(this).parent().parent();

            e.preventDefault();

            var loguser = form.find('input[name="logID"]').val();
            var pass    = form.find('input[name="password"]').val();
            spinal_ajax({
                /* send the ajax call */
                data: {
                    action: 'login',
                    logID: loguser,
                    password: pass,
                    sign_in: 'sign_in'
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
