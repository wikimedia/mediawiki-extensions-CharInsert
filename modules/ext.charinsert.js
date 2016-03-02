( function( $, mw ) {
	var addClickHandlers = function( $content ) {
		$content.find( 'a.mw-charinsert-item-todo' ).each( function() {
			var $elm = $( this ),
				start = $elm.data( 'mw-charinsert-start' ),
				end = $elm.data( 'mw-charinsert-end' );
			$elm.click( function( e ) {
				e.preventDefault();
				mw.toolbar.insertTags( start, end, '' );
			} ).removeClass( 'mw-charinsert-item-todo' );
		} );
	};
	// Normally <charinsert> appears outside of content area.
	// However, we also want to catch things like live preview,
	// so we use both the onready hook and wikipage.content.
	$( function() {
		addClickHandlers( $( document ) );
	} );
	mw.hook( 'wikipage.content' ).add( addClickHandlers );
} )( jQuery, mediaWiki );
