(function( $ ) {
	'use strict';
	$ = jQuery.noConflict();

	/**
	 * All of the code for your admin-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */

	$( function() {
		$( "#sortable" ).sortable();
		$( "#sortable" ).disableSelection();

		$('#sortable-save').click(function() {
			var itemsOrder = [];
			$("#sortable li").each(function(i) { itemsOrder.push($(this).attr('data-id')); });

			$toggleSortableAjax(true);
			$.post(window.location.href, {
				order: itemsOrder.join(','),
				type: 'POST',
				data: {action: 'POST'}
			})
			.success(function(data) { $toggleSortableAjax(false); })
			.error(function(data) { console.log('Error: ' + data); });
		});

		var $toggleSortableAjax = function($hide) {
			$('#sortable-save').prop("disabled", $hide);
			$('#sortable-icon').removeClass($hide?'fa-floppy-o':'fa-spinner').addClass(!$hide?'fa-floppy-o':'fa-spinner');
			$('#sortable-label').text($hide?'Saving...':'Save Order');
			if(!$hide) $('#sortable-result').fadeIn('fast').fadeOut(1500);
		};

		tinymce.init({ selector:'textarea', menubar: false, statusbar: false });


	} );




})( jQuery );
