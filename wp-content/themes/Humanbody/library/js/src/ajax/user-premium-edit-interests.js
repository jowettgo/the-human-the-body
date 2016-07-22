jQuery(document).ready(function() {

    var main_interests = $('#main-interest')
    var categories = $('#interest-categories');

    populate_categories();
    populate_types();


    main_interests.on({
        change: function() {
            populate_categories()
        }
    })

    categories.on({
        change: function() {
            populate_types()
        }
    })


});



function populate_categories() {
    var main_interests = $('#main-interest')
    if(main_interests.length > 0) {
        var categories = $('#interest-categories');
        var types = $('#interest-types');
        /* main interests */
        var main_interests = main_interests.val();
        /* append location */
        var categories_append = categories.parent().find('.menu');
        /* ajax callback on success */
        get_main_categories(main_interests, function(data) {
            var field = '';
            data = JSON.parse(data);

            categories_append.html('');
            for (var id in data) {
                if (data.hasOwnProperty(id)) {


                    field = '<div class="item" data-value="'+id+'">'+data[id]+'</div>';
                    categories_append.append(field);
                }
            }
        });
    }
}
function populate_types() {
    var main_interests = $('#main-interest');


    if(main_interests.length > 0) {

        var categories = $('#interest-categories');
        var types = $('#interest-types');
        /* main interests */
        var categories = categories.val();

        /* append location */
        var types_append = types.parent().find('.menu');
        /* ajax callback on success */
        get_category_types(categories, function(data) {
            var field = '';

            data = JSON.parse(data);
            types_append.html('');
            for (var id in data) {
                if (data.hasOwnProperty(id)) {
                    field = '<div class="item" data-value="'+id+'">'+data[id]+'</div>';
                    types_append.append(field);
                }
            }
        });
    }
}



function get_category_types(categories, callback_success) {
    spinal_ajax({
        data: {
            action: 'category_types',
            categories: categories
        },
        success: function(data) {
            callback_success(data);
        }
    })
}
function get_main_categories(main_interests, callback_success) {
    spinal_ajax({
        data: {
            action: 'main_interests',
            main_interests: main_interests
        },
        success: function(data) {
            callback_success(data);
        }
    })
}
