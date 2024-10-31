<?php

namespace Rioforms\Assets;

class Assets {
	public $main_style_handler = 'rio-forms-generated-styles';

	public function __construct() {
		add_action( 'wp_enqueue_scripts', [ $this, 'styles' ] );
		add_action( 'wp_enqueue_scripts', [ $this, 'scripts' ] );
		//Frontend
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_styles' ] );
		// admin
		add_action( 'admin_enqueue_scripts', [ $this, 'admin_scripts' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'admin_styles' ] );
		// block assets
		add_action( 'enqueue_block_assets', [ $this, 'block_assets' ] );
	}

	/**
	 * Handles the 'enqueue_block_assets' action.
	 *
	 * @since 1.0.0
	 */
	public function block_assets() {
		global $post;

		// If the theme is not a block theme, parse the blocks and set the CSS.
		if ( ! wp_is_block_theme() && ! empty( $post->post_content ) ) {
			do_blocks( $post->post_content );
		}

		wp_enqueue_style(
			'rioform-intlTelInput-styles',
			RIOFORMS_DIR_URL . 'assets/css/deps/intlTelInput.min.css',
			array(),
			RIOFORMS_VERSION
		);

		wp_enqueue_script(
			'rioform-intlTelInput-scripts',
			RIOFORMS_DIR_URL . 'assets/js/deps/intl/intlTelInput.min.js',
			array(),
			RIOFORMS_VERSION,
			false
		);

		wp_enqueue_script(
			'rioform-intlTelInputWithUtils-scripts',
			RIOFORMS_DIR_URL . 'assets/js/deps/intl/intlTelInputWithUtils.min.js',
			array(),
			RIOFORMS_VERSION,
			false
		);
	}

	public function styles() {
		// add styles
		wp_enqueue_style(
			$this->main_style_handler,
			RIOFORMS_DIR_URL . '/assets/css/rio-forms-styles.css',
			array(),
			RIOFORMS_VERSION
		);
	}

	public function scripts() {
		wp_localize_script(
			'rioform-blocks-view-script',
			'rioFormApi',
			array(
				'root'  => esc_url_raw( rest_url() ),
				'nonce' => wp_create_nonce( 'wp_rest' ),
			)
		);
	}

	public function enqueue_scripts() {
		wp_enqueue_script(
			'rioform-tom-select-scripts',
			RIOFORMS_DIR_URL . 'assets/js/deps/tom-select.min.js',
			array(),
			RIOFORMS_VERSION,
			array(
				'strategy' => 'defer',
				'in_footer' => true
			)
		);

		wp_enqueue_script(
			'rioform-block-scripts',
			RIOFORMS_DIR_URL . 'assets/js/blocks/dropdown.js',
			array(),
			RIOFORMS_VERSION,
			array(
				'strategy'  => 'defer',
				'in_footer' => true
			)
		);
	}

	public function enqueue_styles() {
		wp_enqueue_style(
			'rioform-tom-select-styles',
			RIOFORMS_DIR_URL . 'assets/css/deps/tom-select.min.css',
			array(),
			RIOFORMS_VERSION
		);
	}

	public function admin_scripts() {
		global $post;
		$screen = get_current_screen();

		if ( 'edit-rioform' === $screen->id ) {
			wp_enqueue_script(
				'rioform-copy-shortcode',
				RIOFORMS_DIR_URL . '/assets/js/copy-shortcode.js',
				array(),
				RIOFORMS_VERSION,
				true
			);
		}

		if ( 'rioform' === $screen->post_type && 'rioform' === $screen->id ) {
			$preview_nonce = wp_create_nonce( 'rioform-preview-nonce' );
			wp_enqueue_script(
				'form-editor-script',
				RIOFORMS_DIR_URL . '/assets/js/form-editor-script.js',
				array(),
				RIOFORMS_VERSION,
				true
			);
			wp_localize_script(
				'form-editor-script',
				'previewData',
				[
					'postId' => $post->ID,
					'url'    => site_url(),
					'nonce'  => $preview_nonce
				]
			);
		}
		wp_enqueue_editor();

		wp_localize_script(
			'rioform-blocks-script',
			'rioFormData',
			[ 'form_edit_url' => admin_url( 'post.php?post=' ) ]
		);
	}

	public function admin_styles() {
		wp_enqueue_style(
			'rio-forms-admin-styles',
			RIOFORMS_DIR_URL . '/assets/css/admin.css',
			array(),
			RIOFORMS_VERSION
		);
	}
}
