<?php
/**
 * Shortcode Handler
 *
 * @package BuyMore_Dashboard
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

class BuyMore_Shortcode {

    private static $instance = null;

    public static function instance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        add_shortcode( 'buymore_dashboard', array( $this, 'render' ) );
    }

    public function render( $atts ) {
        $atts = shortcode_atts( array(), $atts, 'buymore_dashboard' );

        $renderer = new BuyMore_Renderer();
        ob_start();
        $renderer->render_dashboard();
        return ob_get_clean();
    }
}
