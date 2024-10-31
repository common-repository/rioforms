<?php

namespace Rioforms\Meta;


class Meta {
	private $type = 'rioform';

	public function __construct() {
		add_action( 'init', [ $this, 'register_meta' ] );
	}

	public function register_meta() {
		register_post_meta(
			$this->type,
			'form_title',
			[
				'type'              => 'string',
				'single'            => true,
				'show_in_rest'      => true,
				'sanitize_callback' => 'sanitize_text_field',
				'auth_callback'     => [ $this, 'auth_callback' ],
			]
		);
		register_post_meta(
			$this->type,
			'form_description',
			[
				'type'              => 'string',
				'single'            => true,
				'show_in_rest'      => true,
				'sanitize_callback' => 'sanitize_textarea_field',
				'auth_callback'     => [ $this, 'auth_callback' ],
			]
		);
		register_post_meta(
			$this->type,
			'sent_to_email',
			[
				'type'              => 'string',
				'single'            => true,
				'show_in_rest'      => true,
				'sanitize_callback' => 'sanitize_email',
				'auth_callback'     => [ $this, 'auth_callback' ],
			]
		);
		register_post_meta(
			$this->type,
			'email_subject',
			[
				'type'              => 'string',
				'single'            => true,
				'show_in_rest'      => true,
				'sanitize_callback' => 'sanitize_text_field',
				'auth_callback'     => [ $this, 'auth_callback' ],
			]
		);
		register_post_meta(
			$this->type,
			'email_message_template',
			[
				'type'              => 'string',
				'single'            => true,
				'show_in_rest'      => true,
				'sanitize_callback' => 'wp_kses_post',
				'auth_callback'     => [ $this, 'auth_callback' ],
			]
		);
		register_post_meta(
			$this->type,
			'confirmation_type',
			[
				'type'              => 'string',
				'single'            => true,
				'show_in_rest'      => true,
				'default'           => 'message',
				'sanitize_callback' => 'sanitize_text_field',
				'auth_callback'     => [ $this, 'auth_callback' ],
			]
		);
		register_post_meta(
			$this->type,
			'thank_you_message',
			[
				'type'              => 'string',
				'single'            => true,
				'show_in_rest'      => true,
				'sanitize_callback' => 'wp_kses_post',
				'auth_callback'     => [ $this, 'auth_callback' ],
				'default'           => '<p>Thank you for contact with us.</p>'
			]
		);
		register_post_meta(
			$this->type,
			'redirect_url',
			[
				'type'              => 'string',
				'single'            => true,
				'show_in_rest'      => true,
				'sanitize_callback' => 'sanitize_url',
				'auth_callback'     => [ $this, 'auth_callback' ],
			]
		);

		register_post_meta(
			$this->type,
			'form_block_style',
			[
				'type'              => 'string',
				'single'            => true,
				'show_in_rest'      => true,
				'sanitize_callback' => 'sanitize_text_field',
				'auth_callback'     => [ $this, 'auth_callback' ],
			]
		);
		register_post_meta(
			$this->type,
			'form_block_fonts',
			[
				'type'              => 'string',
				'single'            => true,
				'show_in_rest'      => true,
				'sanitize_callback' => 'sanitize_text_field',
				'auth_callback'     => [ $this, 'auth_callback' ],
			]
		);
		register_post_meta(
			$this->type,
			'form_labels',
			[
				'type'              => 'string',
				'single'            => true,
				'show_in_rest'      => true,
				'sanitize_callback' => 'sanitize_text_field',
				'auth_callback'     => [ $this, 'auth_callback' ],
			]
		);
		register_post_meta(
			$this->type,
			'form_notification',
			[
				'type'          => 'array',
				'single'        => true,
				'show_in_rest'  => [
					'schema' => [
						'type'  => 'array',
						'items' => array(
							'type'       => 'object',
							'properties' => [
								'action_status'          => [
									'type'              => 'string',
									'sanitize_callback' => 'sanitize_text_field',
								],
								'action_name'            => [
									'type'              => 'string',
									'sanitize_callback' => 'sanitize_text_field',
								],
								'sent_to_email'          => [
									'type'              => 'string',
									'sanitize_callback' => 'sanitize_text_field',
								],
								'replay_to_email'          => [
									'type'              => 'string',
									'sanitize_callback' => 'sanitize_text_field',
								],
								'email_subject'          => [
									'type'              => 'string',
									'sanitize_callback' => 'sanitize_text_field',
								],
								'email_message_template' => [
									'type'              => 'string',
									'sanitize_callback' => 'sanitize_text_field',
								],
							],
						),
					],
				],
				'auth_callback' => [ $this, 'auth_callback' ],
			]
		);
	}

	public function auth_callback() {
		return current_user_can( 'edit_posts' );
	}
}
