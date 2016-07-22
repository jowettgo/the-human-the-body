jQuery(document).ready(function() {

    $('.contact-popup-sticky a').fancybox({
		'autoSize': false,
		'width': '750px',
        'helpers' : {
            overlay: {
                locked: false
            }
        },
        afterLoad: function () {
			$('.fancybox-wrap').addClass('show-close');
		}

	});
})
