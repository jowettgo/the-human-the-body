jQuery(document).ready(function() {


    var select = $('#select-category-forum');

    select.ddslick('destroy');
    select = $('#select-category-forum');
    select.ddslick({
        showSelectedHTML: false,

        onSelected: function(data){
            var redirect = data.selectedData.value;
            if(redirect.length > 4) {
                window.location.href = redirect;
            }
        }
    });
})
