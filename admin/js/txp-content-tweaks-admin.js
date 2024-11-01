/**
 * JavaScript used to enhance the usability of the plugin admin page.
 *
 * @link       https://techxplorer.com
 * @since      1.0.0
 *
 * @package    Txp_Content_Tweaks
 * @subpackage Txp_Content_Tweaks/admin/
 */

(function( $ ) {
	'use strict';

	// Bind event handlers once the DOM is ready.
	$( function() {
		// Disable / Enable the hashtag sub options - on load.
		if ( false === $( '#txp-content-tweaks-maptags' ).prop( 'checked' ) ) {
			$( '.txp-content-tweaks-tagoption' ).prop( 'disabled', true );
		}

		// Disable / Enable the hashtag sub options - on change.
		$( '#txp-content-tweaks-maptags' ).change(function(event) {
			if ( false === $( this ).prop( 'checked' ) ) {
				// Disable and untick the sub options.
				$( '.txp-content-tweaks-tagoption' ).prop( 'disabled', true ).prop( 'checked' , false );
			} else {
				$( '.txp-content-tweaks-tagoption' ).prop( 'disabled', false );
			}
		});
	});

})( jQuery );
