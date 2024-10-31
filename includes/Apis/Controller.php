<?php

namespace Rioforms\Apis;

abstract class Controller {
	protected $namespace = 'rio-forms/v2';

	public function authorize( $request ) {
		$capability = is_multisite() ? 'delete_sites' : 'manage_options';
		return current_user_can( $capability );
	}

	public function public( $request ) {
		return true;
	}
}
