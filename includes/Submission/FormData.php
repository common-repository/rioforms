<?php

namespace Rioforms\Submission;

class FormData {
	public $data;
	public $post_id;
	public $form_id;
	public $submitted;
	public $form_data;
	public $post;
	public $notification;
	public $labels;

	public function __construct( $data ) {
		$this->data         = json_decode( $data, true );
		$this->post_id      = (int) $this->data['postId']['value'];
		$this->form_data    = get_post_meta( $this->post_id );
		$this->post         = get_post( $this->post_id );
		$this->notification = unserialize( $this->form_data['form_notification'][0] );
		$this->labels       = json_decode( $this->form_data['form_labels'][0], true );
		$this->init_data();
	}

	/**
	 * Process form data
	 *
	 * @return void
	 */
	private function init_data() {
		$excluded = [ 'formId', 'postId' ];

		foreach ( $this->data as $entry ) {
			if ( ! in_array( $entry['name'], $excluded, true ) ) {
				$this->set_submitted_data( $entry );
			}
		}
	}

	/**
	 * Process submitted data
	 *
	 * @param array $entry
	 *
	 * @return void
	 */
	private function set_submitted_data( $entry ) {
		$value = $entry['value'];
		if ( 'checkbox' === $entry['type'] ) {
			$value = implode( ', ', $value );
		}
		$this->submitted[ $entry['name'] ] = $value;
	}

	/**
	 * Get single data from the object
	 *
	 * @param string $attr
	 *
	 * @return mixed
	 */
	public function get( $attr ) {
		$value = $this->form_data[ $attr ] ?? '';

		return is_array( $value ) ? $value[0] : $value;
	}

	/**
	 * Email message
	 *
	 * @param string $message
	 *
	 * @return string
	 */
	public function get_email_message( $message = '' ) { // phpcs:ignore Generic.Metrics.CyclomaticComplexity
		$message_pattern = $message;

		// return all fields if not pattern found.
		if ( '' === $message_pattern ) {
			return $this->get_default_email_message();
		}

		return $this->replace_placeholder_with_value( $message_pattern );
	}

	/**
	 * Default email message.
	 *
	 * @return string
	 */
	public function get_default_email_message() {
		$message = "";

		foreach ( $this->submitted as $key => $element ) {
			if ( '' === $element ) {
				continue;
			}
			$label   = $this->labels[ $key ];
			$message .= "<p><b>$label</b>: {$element}</p>";
		}

		return $message;
	}

	/**
	 * Sent to email
	 *
	 * @param string $to
	 *
	 * @return string
	 */
	public function get_sent_to_email( $to = '' ) {
		return '' !== $to ? $to : get_bloginfo( 'admin_email' );
	}

	/**
	 * Email subject
	 *
	 * @param string $subject
	 *
	 * @return string
	 */
	public function get_email_subject( $subject = '' ) {
		$subject = '' !== $subject ? $this->replace_placeholder_with_value( $subject ) : $this->post->post_title;

		return $subject;
	}

	/**
	 * Thank you message
	 *
	 * @return string
	 */
	public function get_thank_you_message() {
		$message = $this->get( 'thank_you_message' );

		return ! empty( $message ) ? $message : 'Thank you for contact with us.';
	}

	/**
	 * Form Title
	 *
	 * @return string
	 */
	public function get_form_title() {
		$title = $this->get( 'form_title' );

		return '' !== $title ? $title : $this->post->post_title;
	}

	/**
	 * Submit the form
	 *
	 * @param string $to
	 * @param string $subject
	 * @param string $message
	 * @param array $headers
	 *
	 * @return void
	 */
	public function send_mail( $to, $subject, $message, $headers ) {
		wp_mail( $to, $subject, $message, $headers );
	}

	/**
	 * @return void
	 */
	public function get_replay_to_email( $content ) {
		return $this->replace_placeholder_with_value( $content );
	}

	/**
	 * Replace shortcode with value
	 *
	 * @param $content
	 *
	 * @return string
	 */
	public function replace_placeholder_with_value( $content ) {
		foreach ( $this->submitted as $key => $value ) {
			$content = str_ireplace(
				"{{{$key}}}",
				wp_strip_all_tags( $value ),
				$content
			);
		}

		return $content;
	}
}
