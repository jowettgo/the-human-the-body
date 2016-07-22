jQuery(document).ready(function() {
    $('a.upload-photo').fancybox({
        'autoSize': false,
        'width': '750px',
        afterLoad: function () {
            $('.fancybox-wrap').addClass('show-close');
        },
        helpers: {
           overlay: {
             locked: false
           }
         }
    });
})
jQuery(document).ready(function() {
    $(document).on('change', 'input[name="photo_upload"]',function() {
        var filename = $(this).val();
        $(this).next().text(filename);
    })
})
