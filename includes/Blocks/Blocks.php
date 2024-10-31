<?php

namespace Rioforms\Blocks;

/**
 * Load all blocks
 */
class Blocks {
	protected $rioforms_builder_block = [ 'rioforms/rioform' ];

	protected $riofrom_child_block_list = [
		'rioforms/container',
		'rioforms/text',
		'rioforms/select',
		'rioforms/submit',
		'rioforms/textarea',
		'rioforms/checkbox',
		'rioforms/email',
		'rioforms/number',
		'rioforms/radio',
		'rioforms/url',
		'rioforms/phone',
		'rioforms/heading',
		'rioforms/address',
		'rioforms/country',
		'rioforms/paragraph'
	];

	protected $block_name = 'rioforms/rioform';

	public function __construct() {
		$this->hooks();
	}

	/**
	 * Hooks.
	 * @return void
	 */
	public function hooks() {
		add_action( 'init', [ $this, 'register_blocks' ] );
		add_filter( 'block_categories_all', [ $this, 'block_categories' ] );
		add_action( 'wp_enqueue_scripts', [ $this, 'load_styles_and_scripts' ], 99 );
		add_filter( 'allowed_block_types_all', [ $this, 'form_builder_block' ], 10, 2 );
	}

	/**
	 * Disable form builder block and child block.
	 * from all posttypes except rioform
	 *
	 * @param $allowed_block_types
	 * @param $editor_context
	 *
	 * @return array|mixed
	 */
	public function form_builder_block( $allowed_block_types, $editor_context ) {
		if ( ! isset( $editor_context->post->post_type ) ) {
			return $allowed_block_types;
		}
		if ( $editor_context->post->post_type !== 'rioforms' ) {
			$disallowed_blocks = $this->get_full_block_list();
			// Get all registered blocks if $allowed_block_types is not already set.
			if ( ! is_array( $allowed_block_types ) || empty( $allowed_block_types ) ) {
				$registered_blocks   = \WP_Block_Type_Registry::get_instance()->get_all_registered();
				$allowed_block_types = array_keys( $registered_blocks );
			}

			$filtered_blocks = [];
			foreach ( $allowed_block_types as $block ) {
				if ( ! in_array( $block, $disallowed_blocks, true ) ) {
					$filtered_blocks[] = $block;
				}
			}

			return $filtered_blocks;
		}

		return $allowed_block_types;
	}

	/**
	 * Load assets for the block
	 *
	 * @return void
	 */
	public function load_styles_and_scripts() {
		wp_enqueue_style(
			'rio-forms-styles',
			RIOFORMS_DIR_URL . 'build/forms.css',
			array( 'rioform-blocks-style' ),
			RIOFORMS_VERSION
		);
	}

	/**
	 *  Register blocks
	 */
	public function register_blocks() {
		$dir = RIOFORMS_DIR_URL;

		$rioforms_asset_path                 = RIOFORMS_DIR . '/build/index.asset.php';
		$rioforms_asset_path_front           = RIOFORMS_DIR . '/build/front.asset.php';
		$rioforms_form_editor_script_path    = RIOFORMS_DIR . '/build/editor.asset.php';
		$rioforms_element_editor_script_path = RIOFORMS_DIR . '/build/elementEditorScript.asset.php';
		if ( ! file_exists( $rioforms_asset_path ) ) {
			return;
		}

		// editor script.
		$rioforms_js           = 'build/index.js';
		$rioforms_script_asset = require $rioforms_asset_path;
		wp_register_script(
			'rioform-blocks-script',
			$dir . 'build/index.js',
			$rioforms_script_asset['dependencies'],
			$rioforms_script_asset['version'],
			false
		);
		// Forms Editor Script.
		$rioforms_form_editor_js     = 'build/editor.js';
		$rioforms_form_editor_script = require $rioforms_form_editor_script_path;
		wp_register_script(
			'rio-form-editor-script-other',
			$dir . 'build/editor.js',
			$rioforms_form_editor_script['dependencies'],
			$rioforms_form_editor_script['version'],
			false
		);
		// Elements Editor Script.
		wp_register_script(
			'rio-element-editor-script-other',
			$dir . 'build/elementEditorScript.js',
			[],
			RIOFORMS_VERSION,
			false
		);

		// viewscript.
		$rioforms_script_asset_front = require $rioforms_asset_path_front;
		if ( ! is_admin() ) {
			wp_register_script(
				'rioform-blocks-view-script',
				$dir . 'build/front.js',
				$rioforms_script_asset_front['dependencies'],
				$rioforms_script_asset_front['version'],
				false
			);
		}

		wp_set_script_translations( 'rioform-blocks-script', 'rioforms' );

		// editor css.
		$rioforms_css = '/build/index.css';
		wp_register_style(
			'rioform-blocks-editor-style',
			$dir . 'build/index.css',
			[ 'wp-components' ],
			RIOFORMS_VERSION
		);

		// frontend css.
		wp_register_style(
			'rioform-blocks-style',
			$dir . 'build/style-index.css',
			array(),
			RIOFORMS_VERSION
		);

		$args = [
			'editor_script' => 'rioform-blocks-script',
			'editor_style'  => 'rioform-blocks-editor-style',
			'style'         => 'rioform-blocks-style',
		];

		register_block_type( RIOFORMS_DIR . '/build/blocks/rioforms', $args );
		register_block_type(
			RIOFORMS_DIR . '/build/blocks/rioform',
			[
				'editor_script' => [ 'rioform-blocks-script', 'rio-form-editor-script-other' ],
				'editor_style'  => 'rioform-blocks-editor-style',
				'style'         => 'rioform-blocks-style',
				'view_script'   => 'rioform-blocks-view-script',
			]
		);
		register_block_type( RIOFORMS_DIR . '/build/blocks/container', $args );
		register_block_type( RIOFORMS_DIR . '/build/blocks/text', $args );
		register_block_type( RIOFORMS_DIR . '/build/blocks/email', $args );
		register_block_type( RIOFORMS_DIR . '/build/blocks/url', $args );
		register_block_type( RIOFORMS_DIR . '/build/blocks/number', $args );
		register_block_type( RIOFORMS_DIR . '/build/blocks/textarea', $args );
		register_block_type( RIOFORMS_DIR . '/build/blocks/select', $args );
		register_block_type( RIOFORMS_DIR . '/build/blocks/radio', $args );
		register_block_type( RIOFORMS_DIR . '/build/blocks/checkbox', $args );
		register_block_type( RIOFORMS_DIR . '/build/blocks/phone', $args );
		register_block_type( RIOFORMS_DIR . '/build/blocks/heading', $args );
		register_block_type( RIOFORMS_DIR . '/build/blocks/paragraph', $args );
		register_block_type( RIOFORMS_DIR . '/build/blocks/address', $args );
		register_block_type( RIOFORMS_DIR . '/build/blocks/country', $args );
		register_block_type( RIOFORMS_DIR . '/build/blocks/submit', $args );
	}

	/**
	 * Add block category.
	 *
	 * @param array $categories
	 *
	 * @return array|mixed
	 */
	public function block_categories( $categories ) {
		$category_slugs = wp_list_pluck( $categories, 'slug' );

		return in_array( 'rioforms', $category_slugs, true ) ? $categories : array_merge(
			[
				[
					'slug'  => 'rioforms',
					'title' => __( 'RioForms', 'rioforms' ),
					'icon'  => null,
				],
				[
					'slug'  => 'rio_container',
					'title' => __( 'Container', 'rioforms' ),
					'icon'  => null,
				],
				[
					'slug'  => 'rio_fields',
					'title' => __( 'Fields', 'rioforms' ),
					'icon'  => null,
				],
			],
			$categories
		);
	}

	/**
	 * Get all child block list.
	 *
	 * @return mixed|string[]
	 */
	public function get_block_list() {
		return $this->riofrom_child_block_list;
	}

	/**
	 * Get all block list.
	 *
	 * @return string[]
	 */
	public function get_full_block_list() {
		$full_block_list = array_merge( $this->rioforms_builder_block, $this->riofrom_child_block_list );

		return $full_block_list;
	}
}
