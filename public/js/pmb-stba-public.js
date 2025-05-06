(function( $ ) {
	'use strict';

	/**
	 * All of the code for your public-facing JavaScript source
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

	/**
	 * Handle sticky navigation adjustments
	 */
	$(document).ready(function() {
		// Calculate admin bar height (if present)
		var adminBarHeight = $('#wpadminbar').length ? $('#wpadminbar').height() : 0;
		
		// Set sticky nav top position
		$('.pmb-sticky-nav').css('top', adminBarHeight + 'px');
		
		// Adjust position on window resize
		$(window).resize(function() {
			var adminBarHeight = $('#wpadminbar').length ? $('#wpadminbar').height() : 0;
			$('.pmb-sticky-nav').css('top', adminBarHeight + 'px');
		});
	});
	
})( jQuery );
