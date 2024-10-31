<?php

namespace Rioforms\Assets;

class Inspector {
	private $generator;

	public function __construct( $generator ) {
		$this->generator = $generator;
	}

	/**
	 * Get post content and init search.
	 *
	 * @param $query
	 *
	 * @return void
	 */
	public function inspect( $query ) {
		if ( isset( $query->queried_object->post_content ) ) {
			$this->search_form(
				$query->queried_object->post_content
			);
		}
	}

	/**
	 * Generate styles in the head for
	 * all form blocks.
	 *
	 * @return void
	 */
	public function search_form( $content ) {
		if ( ! $content ) {
			return;
		}

		$has_block = has_block( $this->generator->block_name, $content );
		if ( $has_block ) {
			$blocks = parse_blocks( $content );
			$this->search_block( $blocks );
		}

		$has_shortcode = has_shortcode( $content, $this->generator->shortcode_tag );
		if ( $has_shortcode ) {
			$this->search_shortcode( $content );
		}
	}

	/**
	 * Search for shortcode in the post content.
	 *
	 * @param $content
	 *
	 * @return void
	 */
	public function search_shortcode( $content ) {
		$shortcode_regx = '/\[' . $this->generator->shortcode_tag . '\s.*?]/';

		if ( preg_match_all( $shortcode_regx, $content, $shortcodes ) ) {
			foreach ( $shortcodes[0] as $shortcode ) {
				$form_id = $this->get_id_from_shortcode( $shortcode );
				$this->generator->set_fonts( $form_id );
				$this->generator->set_style( $form_id );
			}
		}
	}

	/**
	 * Search for blocks in the post_content.
	 *
	 * @param $blocks
	 *
	 * @return void
	 */
	public function search_block(
		$blocks
	) { // phpcs:ignore Generic.Metrics.CyclomaticComplexity
		foreach ( $blocks as $block ) {
			if ( ! empty( $block['innerBlocks'] ) && $block['blockName'] !== $this->generator->block_name ) {
				$this->search_block( $block['innerBlocks'] );
			} elseif ( $block['blockName'] === $this->generator->block_name ) {
				if ( ! isset( $block['attrs']['postId'] ) ) {
					continue;
				}
				$this->generator->set_fonts( $block['attrs']['postId'] );
				$this->generator->set_style( $block['attrs']['postId'] );
			}
		}
	}

	/**
	 * Extract the id from string.
	 *
	 * @param $shortcode
	 *
	 * @return string
	 */
	public function get_id_from_shortcode( $shortcode ) {
		if ( preg_match_all( '!\d+!', $shortcode, $matches ) ) {
			return $matches[0][0];
		}

		return '';
	}
}
