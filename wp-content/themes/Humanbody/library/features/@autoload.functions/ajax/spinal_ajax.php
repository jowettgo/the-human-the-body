<?php
add_action( 'wp_footer', 'spinal_ajax_js_function_min' , 0);
// add_action( 'wp_footer', 'spinal_ajax_js_function' , 0);



function spinal_ajax_js_function_min() {
    ?>
    <script type="text/javascript">
        function spinal_ajax(a){var n=jQuery.ajax({url:"<?php echo admin_url('admin-ajax.php'); ?>",method:"POST",data:a.data});n.done(function(n,i,u){a.success(n)}),n.fail(function(n,i,u){a.fail(i,u)})}
    </script>
    <?php
}

function spinal_ajax_js_function() {
    ?>
    <script type="text/javascript">
        function spinal_ajax(options) {
            var s_ajax = jQuery.ajax({
                url: "<?php echo admin_url('admin-ajax.php'); ?>",
                method: "POST",
                data: options.data
            });
            /* function on done */
            s_ajax.done(function(returnedData, textstatus, jqXHR) {
                options.success(returnedData)
            });
            s_ajax.fail(function(jqXHR, textstatus, error) {
                options.fail(textstatus, error)
            });
        }
    </script>
    <?php
}
