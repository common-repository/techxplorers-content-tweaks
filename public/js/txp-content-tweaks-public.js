/**
 * JavaScript used to enhance the usability of the public plugin content.
 *
 * @link       https://techxplorer.com
 * @since      1.0.0
 *
 * @package    Txp_Content_Tweaks
 * @subpackage Txp_Content_Tweaks/public
 */

(function( $ ) {
	'use strict';

	/* Add the public facing JavaScript Handlers */
	$( function(){
		/* Add the post stats JavaScript */
		$( '.txp-content-tweaks-post-stats p' ).click( function( event ) {
			event.preventDefault();
			$( '.txp-content-tweaks-post-stats-inner' ).toggle( 'slow' );
		});
	});

})( jQuery );
