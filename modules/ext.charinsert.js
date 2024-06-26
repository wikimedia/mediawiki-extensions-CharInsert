( function () {
	let $currentFocused;
	function addClickHandlers( $content ) {
		$content.find( 'a.mw-charinsert-item' ).each( function () {
			const $item = $( this );
			if ( $item.data( 'mw-charinsert-done' ) ) {
				return;
			}
			$item.on( 'click', ( e ) => {
				e.preventDefault();
				if ( $currentFocused.length ) {
					$currentFocused.textSelection(
						'encapsulateSelection', {
							pre: $item.data( 'mw-charinsert-start' ),
							peri: '',
							post: $item.data( 'mw-charinsert-end' )
						}
					);
				}
			} )
				.data( 'mw-charinsert-done', true )
				.attr( 'href', '#' );
		} );
	}
	// Normally <charinsert> appears outside of content area.
	// However, we also want to catch things like live preview,
	// so we use both the onready hook and wikipage.content.
	$( () => {
		// eslint-disable-next-line no-jquery/no-global-selector
		$currentFocused = $( '#wpTextbox1' );
		// Apply to dynamically created textboxes as well as normal ones
		$( document ).on( 'focus', 'textarea, input:text', function () {
			$currentFocused = $( this );
		} );
		addClickHandlers( $( document ) );
	} );
	mw.hook( 'wikipage.content' ).add( addClickHandlers );
}() );
