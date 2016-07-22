jQuery(document).ready(function () {
    $('.radio-circle').on({
        click: function() {
            check_radio($(this).children('input'));
        }
    })

    $('.radio-circle input').on({
        change: function() {
            check_radio($(this));
        }
    })
    $('.radio-circle input').each(function() {
        var selector = $(this);
        if(selector.is(':checked')) {
            selector.parent().addClass('active');
        }
    });
    function check_radio(selector) {
        if(selector.is(':checked')) {
            $('.radio-circle').removeClass('active');
            selector.parent().addClass('active');
        }
        else {
            selector.parent().removeClass('active');
        }
    }
})
