jQuery(document).ready(function() {
	
	if($('#conversation-form #reply-message').length > 0){
		setTimeout(function(){
			$("html, body").animate({
	            scrollTop: $('#conversation-form #reply-message').offset().top
	     	}, 600);	
		}, 400);
	}
	
	
	
    $(document).on('click', '.delete-message', function() {
        var mid = $(this).attr('data-mid');
        remove_message(mid, $(this));
    });
    
    $(document).on('click', '.highlight-message', function() {
        var mid = $(this).attr('data-mid');
        var val = $(this).attr('data-val');
        var text = val == 1?'unhighlight':'highlight';
        hightlight_message(mid, val, $(this), text);
    });
    
    $(document).on('click', '.cancel-edit', function() {
		var el = $(this);
		el.parent().children().not('p').hide();
		el.parent().find('p').show();
	});
    
    function remove_message(mid, selector) {
        spinal_ajax({
            data: {
                action: 'removemessages',
                mid: mid
            },
            success: function(data) {
                selector.parent().parent().parent().fadeOut(400)
            },
            fail: function(status, error) {

            }
        })
    }
    
    function hightlight_message(mid, value, selector, text) {
        spinal_ajax({
            data: {
                action: 'highlightmessages',
                mid: mid,
                status: value
            },
            success: function(data) {
            	value == 1?selector.parent().parent().parent().addClass('highlighted-message'):selector.parent().parent().parent().removeClass('highlighted-message');
                selector.find('span').html(text);
                var val = value==1?0:1;
                selector.attr('data-val', val);
                
                var el = selector;
				el.parent().children().not('p').hide();
				el.parent().find('p').show();
            },
            fail: function(status, error) {

            }
        })
    }

})
