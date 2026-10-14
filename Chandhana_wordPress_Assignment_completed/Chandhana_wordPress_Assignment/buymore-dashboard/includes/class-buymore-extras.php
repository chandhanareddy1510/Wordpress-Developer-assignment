<?php
/**
 * Completion layer: full-width layout, image defaults, interaction scripts.
 *
 * Added on top of the original plugin without modifying its existing files.
 * Loads the second stylesheet/script after the originals and wires the
 * "Images & Links" admin screen (see class-buymore-images.php).
 *
 * @package BuyMore_Dashboard
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

require_once BUYMORE_PLUGIN_DIR . 'includes/class-buymore-images.php';

class BuyMore_Extras {

    private static $instance = null;

    public static function instance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        add_filter( 'body_class', array( $this, 'body_class' ) );
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue' ), 20 );
        BuyMore_Images::instance();

        // The original loader creates BuyMore_CPT *during* the 'init' action, so its own
        // 'init' callback is added too late and the post type never registers. Creating the
        // instance here (before 'init' fires) lets it register normally.
        BuyMore_CPT::instance();
    }

    /** True when the current singular post contains the dashboard shortcode. */
    private function is_dashboard_page() {
        global $post;
        return is_a( $post, 'WP_Post' ) && has_shortcode( $post->post_content, 'buymore_dashboard' );
    }

    public function body_class( $classes ) {
        if ( $this->is_dashboard_page() ) {
            $classes[] = 'bm-page';
        }
        return $classes;
    }

    public function enqueue() {
        if ( ! $this->is_dashboard_page() ) {
            return;
        }
        wp_enqueue_style(
            'buymore-layout',
            BUYMORE_PLUGIN_URL . 'assets/css/frontend-layout.css',
            array( 'buymore-frontend' ),
            BUYMORE_VERSION
        );
        wp_add_inline_style( 'buymore-layout', BuyMore_Images::css_variables() );

        wp_enqueue_script(
            'buymore-extras',
            BUYMORE_PLUGIN_URL . 'assets/js/frontend-extras.js',
            array( 'buymore-frontend' ),
            BUYMORE_VERSION,
            true
        );
        wp_localize_script( 'buymore-extras', 'BuyMoreExtras', array(
            'links' => BuyMore_Images::links(),
            'i18n'  => array(
                'added'      => __( 'Added to cart:', 'buymore-dashboard' ),
                'cartEmpty'  => __( 'Your cart is empty.', 'buymore-dashboard' ),
                'cartItems'  => __( 'Items in your cart:', 'buymore-dashboard' ),
                'noResults'  => __( 'No products match your search.', 'buymore-dashboard' ),
                'browse'     => __( 'Browse our latest picks below.', 'buymore-dashboard' ),
                'search'     => __( 'Search products…', 'buymore-dashboard' ),
                'sortLabel'  => __( 'Sort products', 'buymore-dashboard' ),
                'sortDefault'=> __( 'Default order', 'buymore-dashboard' ),
                'sortLow'    => __( 'Price: low to high', 'buymore-dashboard' ),
                'sortHigh'   => __( 'Price: high to low', 'buymore-dashboard' ),
            ),
        ) );
    }
}

BuyMore_Extras::instance();
