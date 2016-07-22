jQuery(document).ready(function() {
    $(document).on('click','.add-together',  function() {
            var id = $(this).attr('data-u');
            $('#get-together-input').val(id);
        })
});
