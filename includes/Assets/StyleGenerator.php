<?php

namespace Rioforms\Assets;

class StyleGenerator {
	public $inline_styles = 'rioforms-inline-styles';


	public $css = '';
	public $fonts = [];

	public function __construct() {
		add_filter( 'render_block_data', [ $this, 'set_blocks_css' ], 10 );
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue' ] );
		add_action( 'enqueue_block_assets', [ $this, 'block_assets' ], 10 );
	}

	/**
	 * @param $block
	 *
	 * @return mixed
	 */
	public function set_blocks_css( $block ) {
		if ( isset( $block['blockName'] ) && strpos( $block['blockName'], 'rioforms' ) !== false ) {
			if ( isset( $block['attrs']['styles'] ) ) {
				$this->css .= $block['attrs']['styles'];
			}
			if ( $block['blockName'] === 'rioforms/rioform' ) {
				$this->fonts[] = $block['attrs']['fontFamily'] ?? '';
			}
		}

		return $block;
	}

	/**
	 * @return void
	 */
	public function enqueue() {
		if ( ! empty( $this->css ) ) {
			wp_add_inline_style( rioforms()->assets->main_style_handler, $this->css );
		}
	}

	/**
	 * @return void
	 */
	public function block_assets() {
		$url_param = implode( '&', $this->fonts ) . '&display=swap';
		if ( $this->fonts ) {
			$this->generate_fonts( $url_param );
		}
	}

	/**
	 * @param $fonts
	 *
	 * @return void
	 */
	public function generate_fonts( $fonts ) {
		$font_url = '//fonts.googleapis.com/css2?' . esc_html( $fonts );
		wp_enqueue_style(
			'RIOFORMS_google_fonts',
			$font_url,
			array(),
			'1.0.0'
		);
	}

	/**
	 * @param $styles
	 *
	 * @return void
	 */
	public function generate_css( $styles ) {
		wp_styles()->add_inline_style( rioforms()->assets->main_style_handler, $styles );
		wp_styles()->print_inline_style( rioforms()->assets->main_style_handler );
	}

	public function generate_inline_assetes( $fonts, $style ) {
		$this->generate_fonts( $fonts );
		$this->generate_css( $style );
	}
}
