jQuery(document).ready(function() {
    $('a.submit-idea').fancybox({
        'autoSize': false,
        'width': '750px',
        afterLoad: function () {
            $('.fancybox-wrap').addClass('show-close');
        }
    });
})
