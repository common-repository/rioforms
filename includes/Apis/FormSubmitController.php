<?php

namespace Rioforms\Apis;

use Rioforms\Submission\FormData;

class FormSubmitController extends Controller {
	public function callback( $request ) { // phpcs:ignore Generic.Metrics.CyclomaticComplexity
		$data = $request->get_body();

		if ( ! $data ) {
			return;
		}

		$http_host = str_replace(
			'www.',
			'',
			isset( $_SERVER['HTTP_HOST'] ) ? sanitize_text_field( wp_unslash( $_SERVER['HTTP_HOST'] ) ) : null
		);

		$form       = new FormData( $data );
		$headers    = [ 'Content-Type: text/html; charset=UTF-8' ];
		$from_email = "<wordpress@{$http_host}"; // or <mail@{$http_host}
		$headers[]  = "From: {$form->get_form_title()} {$from_email}";

		if ( is_array( $form->notification ) && ! empty( $form->notification ) ) {
			foreach ( $form->notification as $notification ) {
				if ( 'on' !== $notification['action_status'] ) {
					continue;
				}

				$to        = $form->get_sent_to_email( $notification['sent_to_email'] );
				$subject   = $form->get_email_subject( $notification['email_subject'] );
				$message   = $form->get_email_message( $notification['email_message_template'] );
				$replay_to = $form->get_replay_to_email( $notification['replay_to_email'] );
				$headers[] = "Reply-To: $replay_to";

				$form->send_mail( $to, $subject, $message, $headers );
			}
		} else {
			$to      = $form->get_sent_to_email();
			$subject = $form->get_email_subject();
			$message = $form->get_default_email_message();

			$form->send_mail( $to, $subject, $message, $headers );
		}

		$response = [
			'status'    => 'Success',
			'form'      => $form,
			'data'      => $form->data,
			'thank_you' => $form->get_thank_you_message(),
		];

		if ( $form->get( 'confirmation_type' ) === 'redirect' ) {
			unset( $response['thank_you'] );
			$response['redirect_url'] = $form->get( 'redirect_url' );
		}

		// send api response.
		return new \WP_REST_Response( $response );
	}
}
