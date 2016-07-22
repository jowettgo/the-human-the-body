jQuery(document).ready(function() {

    $('.sing-up-general input[name="email"]').on({
        blur: function() {
            check_email($(this).val(), $(this));
        }
    })

    function check_email(email, selector) {
        spinal_ajax({
            data: {
                action: 'ume',
                mail: email
            },
            success: function(data) {
                if(data == 'true') {
                    selector.addClass('invalid-input');
                    selector.val('')
                    selector.attr('placeholder', selector.attr('data-error'));
                }
                else {
                    selector.removeClass('invalid-input');
                    selector.attr('placeholder', '')
                }
            },
            fail: function(status, error) {

            }
        })
    }
    function check_username(email, selector) {
        spinal_ajax({
            data: {
                action: 'une',
                mail: email
            },
            success: function(data) {
                if(data == 'true') {
                    selector.addClass('invalid-input');
                    selector.val('')
                    selector.attr('placeholder',  selector.attr('data-error'));
                }
                else {
                    selector.removeClass('invalid-input');
                    selector.attr('placeholder', '')
                }

            },
            fail: function(status, error) {

            }
        })
    }

    $('#sign-up').on({
        submit: function(e) {

            if(!$('#terms').is(':checked')) {
                e.preventDefault();

            }
            if($('#confirm-password').val() !== $('#password').val()) {
                e.preventDefault();

            }
            else {

            }
        }
    })
    $('#confirm-password').on({
        change: function() {
            if($('#confirm-password').val() !== $('#password').val()) {
                $(this).addClass('invalid-input');

            }
            else {
                $(this).removeClass('invalid-input');
            }
        }
    })
    $('#password').on({
        blur: function() {
            if($(this).val().length < 6) {
                $(this).addClass('invalid-input');
            }
            else {
                $(this).removeClass('invalid-input');
            }
        }
    })

})
