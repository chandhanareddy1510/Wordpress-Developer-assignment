<?php
/**
 * Plugin Name: BuyMore Dashboard
 * Plugin URI:  https://example.com/buymore-dashboard
 * Description: A fashion e-commerce dashboard page built as a WordPress plugin. Displays an interactive product exploration interface with dynamic content managed through WordPress CMS.
 * Version:     1.0.0
 * Author:      Chandhana
 * Author URI:  https://example.com
 * License:     GPL-2.0-or-later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: buymore-dashboard
 * Domain Path: /languages
 *
 * @package BuyMore_Dashboard
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Plugin constants.
define( 'BUYMORE_VERSION', '1.0.0' );
define( 'BUYMORE_PLUGIN_FILE', __FILE__ );
define( 'BUYMORE_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'BUYMORE_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'BUYMORE_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );

// Load the main plugin class.
require_once BUYMORE_PLUGIN_DIR . 'includes/class-buymore-plugin.php';

/**
 * Returns the main instance of the plugin.
 *
 * @return BuyMore_Plugin
 */
function buymore_dashboard() {
	return BuyMore_Plugin::instance();
}

// Initialise the plugin.
buymore_dashboard();
