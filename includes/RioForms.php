<?php
namespace Rioforms;

/**
 * Riforms main class
 *
 * @class RioForms
 * @property Blocks\Blocks $blocks
 * @property Apis\Endpoints $apis
 * @property Assets\Assets $assets
 * @property ShortCodes\ShortCodes $shortcodes
 */
final class RioForms {

	private static $instance = null;

	protected $container = [];

	private function __construct() {
		add_action( 'plugin_loaded', [ $this, 'init_plugin' ] );
	}

	public static function get_instance() {
		if ( self::$instance === null ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function __get( $name ) {
		if ( array_key_exists( $name, $this->container ) ) {
			return $this->container[ $name ];
		}
	}

	public function init_plugin() {
		add_action( 'init', [ $this, 'load_dependencies' ], 2 );
	}

	public function load_dependencies() {
		new PostType\Form();
		$this->container['blocks']          = new Blocks\Blocks();
		$this->container['meta']            = new Meta\Meta();
		$this->container['assets']          = new Assets\Assets();
		$this->container['shortcodes']      = new ShortCodes\ShortCodes();
		$this->container['styleGenerator']  = new Assets\StyleGenerator();
		$this->container['apis']            = new Apis\Endpoints();
	}
}



