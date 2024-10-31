<?php

namespace Rioforms\Helpers;

class Utils {
	public static function form_allowed_html() {
		$allowed_attrs = array(
			'class'         	=> true,
			'style'         	=> true,
			'id'            	=> true,
			'name'          	=> true,
			'type'          	=> true,
			'for'           	=> true,
			'x-on:focus'    	=> true,
			'x-on:focusout' 	=> true,
			'x-show'        	=> true,
			'x-text'        	=> true,
			'data-rules'    	=> true,
			'value'        	 	=> true,
			'x-data'        	=> true,
			'x-on:submit'   	=> true,
			'x-init'        	=> true,
			'x-on:input'    	=> true,
			'form-id'       	=> true,
			'x-bind'        	=> true,
			'x-transition' 	 	=> true,
			'step'         	 	=> true,
			'max'          	 	=> true,
			'min'          	 	=> true,
			'placeholder'   	=> true,
			'rows'           	=> true,
			'hidden'            => true,
			'disabled'          => true,
			'selected'          => true,
			'x-bind:class'      => true,
			'initial-country'   => true,
			'phone-number-type' => true,
			'data-searchable'   => true,
			'data-multiple' 	=> true,
			'data-placeholder'  => true,
			'multiple'			=> true,
		);

		$allowed_html = array(
			'div'      => $allowed_attrs,
			'form'     => $allowed_attrs,
			'label'    => $allowed_attrs,
			'span'     => $allowed_attrs,
			'input'    => $allowed_attrs,
			'textarea' => $allowed_attrs,
			'button'   => $allowed_attrs,
			'select'   => $allowed_attrs,
			'option'   => $allowed_attrs,
			'p'        => $allowed_attrs,
			'h1'        => $allowed_attrs,
			'h2'        => $allowed_attrs,
			'h3'        => $allowed_attrs,
			'h4'        => $allowed_attrs,
			'h5'        => $allowed_attrs,
			'h6'        => $allowed_attrs,
		);
		$allowed_html = array_merge( $allowed_html, self::svg_allowed_html() );

		return $allowed_html;
	}

	/**
	 * Returns an array of allowed HTML tags and attributes for SVG elements.
	 *
	 * @return array The array of allowed HTML tags and attributes.
	 */
	public static function svg_allowed_html() {
		return array(
			'svg'   => array(
				'class'               => true,
				'aria-hidden'         => true,
				'aria-labelledby'     => true,
				'role'                => true,
				'xmlns'               => true,
				'width'               => true,
				'height'              => true,
				'viewbox'             => true,
				'preserveaspectratio' => true,
				'version'             => true,
				'fill'                => true,
			),
			'g'     => array( 'fill' => true ),
			'title' => array( 'title' => true ),
			'path'  => array(
				'd'               => true,
				'fill'            => true,
				'stroke'          => true,
				'stroke-linecap'  => true,
				'stroke-width'    => true,
				'stroke-linejoin' => true
			),
			'defs'  => array(),
		);
	}

	/**
	 *
	 * @return void
	 */
	public static function allowed_html() {
		return array(
			'button' => array(
				'class'   => true,
				'type'    => true,
				'id'      => true,
				'onclick' => true,
			),
			'input'  => array(
				'type'     => true,
				'id'       => true,
				'class'    => true,
				'readonly' => true,
				'disabled' => true,
				'value'    => true,
			),
			'span'   => array(
				'class' => true,
				'id'    => true,
			)
		);
	}
}
