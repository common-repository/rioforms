<?php

namespace Rioforms\Apis;

use Rioforms\Apis\FormSubmitController;

class Endpoints {
	protected $namespace = 'rio-forms/v2';

	public function __construct() {
		add_action( 'rest_api_init', [ $this, 'register_routes' ] );
	}

	public function register_routes() {
		$formSubmission = new FormSubmitController();

		register_rest_route(
			$this->namespace,
			'/process-forms/',
			array(
				array(
					'methods'             => 'POST',
					'callback'            => [ $formSubmission, 'callback' ],
					'permission_callback' => [ $formSubmission, 'public' ],
				),
			)
		);
	}
}
