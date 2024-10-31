(
	function( window, wp ) {
		const {subscribe, select, dispatch} = wp.data;
		const link_id = 'rio-form-preview-button';
		// prepare our custom link's html.
		// const url = previewData.url + '/?rioform_preview=true&form_id=' + previewData.postId;
		const url = `${previewData.url}/?rioform_preview=true&form_id=${previewData.postId}&nonce=${previewData.nonce}`;
		const link_html = `<a target="_blank" id="${link_id}" class="components-button" href="#">Preview</a>`;
		// check if gutenberg's editor root element is present.
		var editorEl = document.getElementById( 'editor' );
		if ( !editorEl ) {
			return;
		}
		subscribe( function() {
			const {savePost} = dispatch( 'core/editor' );
			if ( !document.getElementById( link_id ) ) {
				var toolbalEl = editorEl.querySelector( '.editor-header__toolbar' );
				let urlOpen;
				if ( toolbalEl instanceof HTMLElement ) {
					toolbalEl.insertAdjacentHTML( 'afterend', link_html );
					editorEl.querySelector( '#rio-form-preview-button' ).addEventListener( 'click', ( ev ) => {
						ev.preventDefault();
						const save = savePost();
						save.then( () => {
							setTimeout( function() {
								if ( !urlOpen || urlOpen.closed ) {
									urlOpen = window.open( url, '_blank' );
								} else {
									urlOpen.location.reload();
								}
								urlOpen.focus();
							}, 1000 );
						} );
					} );
				}
			}
		} );
	}
)( window, wp );
