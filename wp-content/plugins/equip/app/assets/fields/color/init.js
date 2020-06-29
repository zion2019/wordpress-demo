(
	function ( $ ) {
		'use strict';

		$( '.equip-color' ).spectrum();

		$( document ).on( 'change', '.equip-color', function ( e ) {
			var $this = $( this );
			$this.spectrum( 'set', $this.val() );
		} );

	}
)( jQuery );