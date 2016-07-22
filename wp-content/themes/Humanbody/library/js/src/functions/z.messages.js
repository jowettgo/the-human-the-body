jQuery(window).ready(function() {
    $('.messages-table.collection tr').on('click', function() {
        var goto = $(this).attr('data-messages');
        window.location.href = goto;
    })
    $('.delete-message').unbind('click');
})
