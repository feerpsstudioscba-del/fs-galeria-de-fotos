<?php
/**
 * Plugin Name: FS Galeria Fotos
 * Description: Widget Elementor de galeria imobiliaria com mosaico, lightbox e marca d'agua visual.
 * Version: 1.0.0
 * Author: FS
 * Text Domain: fs-galeria-fotos
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'FS_GALERIA_FOTOS_VERSION', '1.0.0' );
define( 'FS_GALERIA_FOTOS_FILE', __FILE__ );
define( 'FS_GALERIA_FOTOS_PATH', plugin_dir_path( __FILE__ ) );
define( 'FS_GALERIA_FOTOS_URL', plugin_dir_url( __FILE__ ) );

require_once FS_GALERIA_FOTOS_PATH . 'includes/class-plugin.php';

add_action( 'plugins_loaded', array( 'FS_Galeria_Fotos_Plugin', 'instance' ) );
