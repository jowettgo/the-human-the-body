<?php
add_action( 'wp_footer', 'subscribe_js' , 99999);
add_action( 'wp_ajax_subscribe', 'ajax_subscribe' );






function subscribe_js() {
?>
<script type="text/javascript" >
jQuery(document).ready(function($) {
    var url = "<?php echo admin_url('admin-ajax.php'); ?>";


    var selectorid = '#subscribe-ajax';
    var updateId = '#update-ajax';
    var submitID = '#submit-ajax';
    subscribe(url, selectorid, updateId, submitID);

	//end join meeting
    function subscribe(url, selectorid, updateID, submitID) {
        $(submitID).on({
            click: function() {
                selector = $(selectorid);
                var email = selector.val();
                console.log(email.length);
                if(email.length > 0) {

                    var activetext = selector.attr('data-text-active');
                    var inactivetext = selector.attr('data-text-inactive');

                    var action = 'subscribe';


                    /* ajax method */
                    var ajaxSend = jQuery.ajax({
                        url: url,
                        method: "POST",
                        data: {email: email, action : action}
                    });
                    /* on success */
                    ajaxSend.done(function(returnedData, textStatus, jqXHR) {

                        /* get active and inactive text */
                        if(returnedData == 'Thank You For Subscribing') {
                        console.log(returnedData, textStatus);
                            selector.val(returnedData).attr('disabled', 'disabled')
                            $(submitID).fadeOut(300);
                        }
                        else {
                            selector.val(returnedData);
                        }
                    });
                    /* on fail */
                    ajaxSend.fail(function(jqXHR, textStatus, errorThrown) {
                        console.log(errorThrown);
                    });
                }
            }

        })
    }
})
</script>
<?php

}
function ajax_subscribe() {
    /* sanitize post */
    $_POST  = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
    $email = $_POST['email'];
        $subscribe  = new spinal_subscribe_csv_download();
        echo $subscribe->add($email);
    die();
}


?>
