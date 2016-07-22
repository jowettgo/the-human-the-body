jQuery(document).ready(function() {
    var selector = $('.change-country');
    var update = $('.change-cities .menu');
    selector.on({
        change: function() {
            var cid = $(this).val();

            spinal_ajax({
                data: {
                    action: 'loadcities',
                    cid: cid,
                },
                success: function(data) {
                    update.html(data);

                },
                fail: function(status, error) {
                }
            })

        }
    })
})
