jQuery(document).ready(function() {
    /* start map check
    ------------------------------------*/
    if($('#mapdiv').length > 0) {
		var map = AmCharts.makeChart("mapdiv", {
			type : "map",
			theme : "dark",
			pathToImages : "http://cdn.amcharts.com/lib/3/images/",
			panEventsEnabled : true,
			backgroundColor : "transparent",
			backgroundAlpha : 1,
			zoomControl : {
				zoomControlEnabled : true,
                maxZoomLevel: 8,
                zoomDuration: 0.5,
			},
			dataProvider : {
				map : "worldHigh",
				getAreasFromMap : true,
				areas : [],
                autoResize: true,
                zoomDuration: 0.5,
			},
			areasSettings : {
				autoZoom : true,
				color : "#fff",
				colorSolid : "#84ADE9",
				selectedColor : "rgba(28,28,28, 0.8)",
				outlineColor : "#c5c2c3",
				rollOverColor : "rgba(28,28,28, 0.35)",
				rollOverOutlineColor : "#000000"
			}
		});
        //$('.search-profile').fadeOut(0);


        /* country ajax on click
        -----------------------------------------*/
        /* bind click event handler */
		map.addListener("clickMapObject", function (event) {
            /* get selected country clicked */
		    var iso = event.mapObject.id;
			$('#map-country').val(iso);
            var title = $('.ajax-country-name').text('CITIES FROM ' + event.mapObject.title);
            /* show all the filters */
            spinal_ajax({
                data: {
                    action: 'loadcitiesiso',
                    iso: iso,
                },
                success: function(data) {
                    $('.search-profile').fadeIn(400);
                    /* update with the new html */
                    $('.map-ajax-cities .menu').html(data)
                }
            })

		});
        /* end country ajax on click
        -----------------------------------------*/

        /* filter user by city and interests
        -----------------------------------------*/
        $('.search-profile').on({
            submit: function(e) {
                e.preventDefault();
                var cities = $('#cities-selected').val();
                var first_interests = $('#main-interest').val();
                var second_interests = $('#interest-categories').val();
                var third_interests = $('#interest-types').val();
                var country = $('#map-country').val();

                spinal_ajax({
                    data: {
                        action: 'filterusers',
                        cities: cities,
                        first_interests: first_interests,
                        second_interests: second_interests,
                        third_interests: third_interests,
                        country: country
                    },
                    success: function(data) {

                        $('#users-found').html(data)
                    }
                })

            }
        })


    /* end map check
    ------------------------------------*/
	}
});
