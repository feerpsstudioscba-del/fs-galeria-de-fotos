<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class FS_Galeria_Fotos_Plugin {
	private static $instance = null;

	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	private function __construct() {
		add_action( 'elementor/init', array( $this, 'init' ) );
		add_action( 'elementor/frontend/after_register_styles', array( $this, 'register_styles' ) );
		add_action( 'elementor/frontend/after_register_scripts', array( $this, 'register_scripts' ) );
	}

	public function init() {
		add_action( 'elementor/widgets/register', array( $this, 'register_widgets' ) );
	}

	public function register_styles() {
		wp_register_style(
			'fs-imob-gallery',
			FS_GALERIA_FOTOS_URL . 'assets/css/fs-imob-gallery.css',
			array(),
			FS_GALERIA_FOTOS_VERSION
		);
	}

	public function register_scripts() {
		wp_register_script(
			'fs-imob-gallery',
			FS_GALERIA_FOTOS_URL . 'assets/js/fs-imob-gallery.js',
			array(),
			FS_GALERIA_FOTOS_VERSION,
			true
		);
	}

	public function register_widgets( $widgets_manager ) {
		require_once FS_GALERIA_FOTOS_PATH . 'includes/widgets/class-fs-imob-gallery-widget.php';
		$widgets_manager->register( new \FS_Galeria_Fotos\Widgets\FS_Imob_Gallery_Widget() );
	}
}
