jQuery(document).ready(function() {
    $('.fancybox-popup').on({
        click: function(e) {
            e.preventDefault();
        }
     })
    $('.fancybox-popup').fancybox({
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
