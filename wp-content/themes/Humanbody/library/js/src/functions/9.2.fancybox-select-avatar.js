jQuery(document).ready(function() {
    //start avatar popup
    $('.chose-wrapper a').fancybox({
        'autoSize': false,
        'width': '750px',
        helpers: {
           overlay: {
             locked: false
           }
         }
    });
        $('.select-avatar').unbind('click');
    	// start take avatar img attr and send it to hidden field value
    	$('.select-avatar').on('click', function(e){
    		e.preventDefault();

            var activeImg = $('.avatar-outer-wrap.active img').attr('src');
    		var activeId = $('.avatar-outer-wrap.active img').attr('alt');

    		$('#profile-pic').val(activeId);
            $('.profile-pic-upload').attr('src',activeImg);
    		$('.fancybox-close').click();
    	});
})
