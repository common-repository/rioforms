function handleCopyShortcode( button ) {
	const shortcode = button.nextElementSibling;
	shortcode.select();
	document.execCommand('copy');
	button.classList.add( 'shortcode-copied' );
	setTimeout( () => {
		button.classList.remove( 'shortcode-copied' );
	}, 2000)
}
