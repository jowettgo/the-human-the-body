jQuery(document).ready(function () {
    $('a.js-share').unbind('click');
    $('a.js-share').on('click', function(e) {
        e.preventDefault();
        var url = $(this).attr('href');
        popup(url);
    })
})

function popup(url) {
    var ww = $(window).width();
    var wh = $(window).height();
    var pw = 490;
    var ph = 400;
    var top = Math.ceil((wh-ph)/2);
    var left = Math.ceil((ww-pw)/2);
    window.open(url, "_blank", "toolbar=yes, scrollbars=yes, resizable=yes, top="+ top +", left="+ left +", width="+ pw +", height="+ ph);
}
