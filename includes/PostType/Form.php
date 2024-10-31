<?php

namespace Rioforms\PostType;

class Form {

	public function __construct() {
		add_action( 'init', [ $this, 'post_type' ] );
		add_filter( 'allowed_block_types_all', [ $this, 'allowed_blocks' ], 25, 2 );
		add_action( 'manage_rioform_posts_columns', [ $this, 'add_column' ], 10, 2 );
		add_action( 'manage_rioform_posts_custom_column', [ $this, 'add_column_content' ], 10, 2 );
		add_action( 'template_include', [ $this, 'preview' ], 9999 );
		add_filter( 'wp_insert_post_data', [ $this, 'insert_post_data' ], 10, 1 );
		add_filter( 'enter_title_here', [ $this, 'change_title_placeholder' ], 10, 1 );
	}

	public function post_type() {
		$labels = [
			'name'          => __( 'RioForms', 'rioforms' ),
			'singular_name' => __( 'Form', 'rioforms' ),
			'add_new'       => __( 'Add New Form', 'rioforms' ),
			'add_new_item'  => __( 'Add New Form', 'rioforms' ),
			'edit_item'     => __( 'Edit Form', 'rioforms' ),
			'new_item'      => __( 'Add New Form', 'rioforms' ),
			'all_items'     => __( 'Forms', 'rioforms' ),
			'view_item'     => __( 'View Form', 'rioforms' ),
			'search_items'  => __( 'Search Form', 'rioforms' ),
		];
		$args   = [
			'labels'                => $labels,
			'public'                => false,
			'show_ui'               => true,
			'menu_icon'             => 'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIyNCIgaGVpZ2h0PSIyNCIgdmlld0JveD0iMCAwIDI0IDI0IiBmaWxsPSJub25lIj4KCTxwYXRoIGZpbGwtcnVsZT0iZXZlbm9kZCIgY2xpcC1ydWxlPSJldmVub2RkIgoJCSAgZD0iTTQgMGE0IDQgMCAwIDAtNCA0djE2YTQgNCAwIDAgMCA0IDRoMTZhNCA0IDAgMCAwIDQtNFY0YTQgNCAwIDAgMC00LTR6bTMuNzUgNGExLjc1IDEuNzUgMCAxIDAgMCAzLjVoOWExLjc1IDEuNzUgMCAxIDAgMC0zLjV6bTAgNmExLjc1IDEuNzUgMCAxIDAgMCAzLjVoN2ExLjc1IDEuNzUgMCAxIDAgMC0zLjV6TTYgMTcuNzVhMS43NSAxLjc1IDAgMSAxIDMuNSAwIDEuNzUgMS43NSAwIDAgMS0zLjUgMCIKCQkgIGZpbGw9IiM5Y2ExYTgiLz4KPC9zdmc+Cgo=',
			'publicly_queryable'    => false,
			'menu_position'         => 5,
			'supports'              => array( 'title', 'editor', 'custom-fields' ),
			'has_archive'           => true,
			'show_in_admin_bar'     => true,
			'show_in_nav_menus'     => true,
			'query_var'             => true,
			'show_in_rest'          => true,
			'rest_controller_class' => 'WP_REST_Posts_Controller',
			'template'              => [
				[
					'rioforms/rioform',
					[],
				],
			],
		];

		register_post_type( 'rioform', $args );
	}

	/**
	 * @param $allowed_blocks
	 * @param $editor_context
	 *
	 * @return mixed|string[]
	 */
	public function allowed_blocks( $allowed_blocks, $editor_context ) {
		if ( 'core/edit-post' === $editor_context->name && 'rioform' === $editor_context->post->post_type ) {
			return rioforms()->blocks->get_block_list();
		}

		return $allowed_blocks;
	}

	/**
	 * Add an extra column for shortcode
	 *
	 * @param array $columns
	 *
	 * @return mixed
	 */
	public function add_column( $columns ) {
		unset( $columns['date'] );
		$columns['form_shortcode'] = __( 'ShortCode', 'rioforms' );

		return $columns;
	}

	/**
	 * Column content
	 *
	 * @param array $column
	 * @param string $post_id
	 *
	 * @return void
	 */
	public function add_column_content( $column, $post_id ) {
		switch ( $column ) {
			case 'form_shortcode':
				$shortcode      = sprintf( '
					<input class="rio-form-shortcode" readonly value="%1$s"/>',
					"[rioform id='" . $post_id . "']"
				);
				$button_to_copy = '<button type="button" class="rio-form-copy-shortcode" onclick="handleCopyShortcode(this)">
					<span class="copy dashicons dashicons-admin-page"></span>
					<span class="copied dashicons dashicons-saved"></span>
				</button>';
				echo sprintf(
					'<div class="rioform-shortcode-wrap">%1$s%2$s</div>',
					wp_kses( $button_to_copy, \Rioforms\Helpers\Utils::allowed_html() ),
					wp_kses( $shortcode, \Rioforms\Helpers\Utils::allowed_html() )
				);
				break;
		}
	}

	/**
	 *
	 * @param $template
	 *
	 * @return mixed|string
	 */
	public function preview( $template ) {
		$is_preview = isset( $_GET['rioform_preview'] );

		if ( $is_preview ) {
			if ( isset( $_GET['nonce'] ) && wp_verify_nonce( $_GET['nonce'], 'rioform-preview-nonce' ) ) {
				$file_name = 'rioform-preview.php';

				if ( is_user_logged_in() ) {
					$template = RIOFORMS_PATH . '/template/' . $file_name;
				}
			}
		}

		return $template;
	}

	/**
	 * Add a title on a new form creation.
	 *
	 * @param $data
	 * @param $post
	 *
	 * @return mixed
	 */
	public function insert_post_data( $data ) {
		if ( 'rioform' === $data['post_type'] && empty( $data['post_title'] ) ) {
			$data['post_title'] = 'Contact form';
		}

		return $data;
	}

	/**
	 * Change the form title placeholder.
	 *
	 * @param $title
	 *
	 * @return void
	 */
	public function change_title_placeholder( $title ) {
		$screen = get_current_screen();
		if ( $screen->post_type == 'rioform' ) {
			$title = __( 'Form title', 'rioforms' );
		}

		return $title;
	}
}
