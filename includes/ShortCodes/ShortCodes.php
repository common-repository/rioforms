<?php

namespace Rioforms\ShortCodes;

use Rioforms\Helpers\Utils;

class ShortCodes {
	public function __construct() {
		add_shortcode( 'rioform', [ $this, 'render' ] );
	}

	/**
	 * Render shortcode output
	 *
	 * @param array $attrs
	 *
	 * @return false|string
	 */
	public function render( $attrs ) {
		$id = (int) $attrs['id'];
		if ( ! $id ) {
			return;
		}

		$post = get_post( $id, OBJECT, 'edit' );

		if ( ! $post || 'publish' !== $post->post_status ) {
			return '<h2>No form found</h2>';
		}
		$parsed_blocks = parse_blocks( $post->post_content );
		$block         = $parsed_blocks[0];
		$styles        = $block['attrs']['styles'] ?? '';
		$styles        .= $block['attrs']['innerBlocksStyles'] ?? '';
		$fonts         = $block['attrs']['fontFamily'] ?? '';

		rioforms()->styleGenerator->generate_inline_assetes( $fonts, $styles );

		ob_start();
		echo wp_kses( render_block( $block ), Utils::form_allowed_html() );

		return ob_get_clean();
	}
}
