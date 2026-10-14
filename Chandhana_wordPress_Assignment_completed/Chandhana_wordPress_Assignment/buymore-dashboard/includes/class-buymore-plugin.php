<?php
/**
 * Main Plugin Class
 *
 * @package BuyMore_Dashboard
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

class BuyMore_Plugin {

    private static $instance = null;

    public static function instance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        $this->includes();
        $this->init_hooks();
    }

    private function includes() {
        require_once BUYMORE_PLUGIN_DIR . 'includes/class-buymore-cpt.php';
        require_once BUYMORE_PLUGIN_DIR . 'includes/class-buymore-renderer.php';
        require_once BUYMORE_PLUGIN_DIR . 'includes/class-buymore-admin.php';
        require_once BUYMORE_PLUGIN_DIR . 'includes/class-buymore-shortcode.php';
        require_once BUYMORE_PLUGIN_DIR . 'includes/class-buymore-extras.php';
    }

    private function init_hooks() {
        register_activation_hook( BUYMORE_PLUGIN_FILE, array( $this, 'activate' ) );
        register_deactivation_hook( BUYMORE_PLUGIN_FILE, array( $this, 'deactivate' ) );
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_frontend_assets' ) );
        add_action( 'plugins_loaded', array( $this, 'load_textdomain' ) );
        add_action( 'init', array( $this, 'init' ) );
    }

    public function init() {
        BuyMore_CPT::instance();
        BuyMore_Admin::instance();
        BuyMore_Shortcode::instance();
    }

    public function activate() {
        BuyMore_CPT::instance()->register_post_types();
        flush_rewrite_rules();
        $this->create_default_content();
    }

    public function deactivate() {
        flush_rewrite_rules();
    }

    private function create_default_content() {
        // Create the dashboard page.
        $existing = get_page_by_path( 'buymore-dashboard' );
        if ( ! $existing ) {
            wp_insert_post( array(
                'post_title'   => 'BuyMore Dashboard',
                'post_name'    => 'buymore-dashboard',
                'post_content' => '[buymore_dashboard]',
                'post_status'  => 'publish',
                'post_type'    => 'page',
            ) );
        }

        // Default settings.
        if ( false === get_option( 'buymore_settings' ) ) {
            update_option( 'buymore_settings', array(
                'site_name'      => 'BuyMore',
                'orders_count'   => '37',
                'orders_period'  => 'Last 7 days',
                'username'       => 'Ryana',
                'hero_title'     => 'GET UP TO 50% OFF',
                'hero_button'    => 'Get Discount',
                'promo_title'    => "Winter's weekend",
                'promo_subtitle' => 'keep it casual',
                'offers_button'  => 'Avail Offers',
                'bold_title'     => 'Bring Bold Fashion',
                'bold_subtitle'  => 'Layers on Layers',
            ) );
        }

        $this->seed_products();
    }

    private function seed_products() {
        $existing = get_posts( array( 'post_type' => 'buymore_product', 'numberposts' => 1, 'post_status' => 'publish' ) );
        if ( ! empty( $existing ) ) { return; }

        $products = array(
            array( 'title' => 'WMX Rubber Zebra sandal',       'price' => '36', 'category' => 'Women', 'label' => 'Our Picks' ),
            array( 'title' => 'Supper Skinny jogger in brown',  'price' => '89', 'category' => 'Men',   'label' => 'Your Choice' ),
            array( 'title' => 'Orange Turtleneck Sweater',      'price' => '59', 'category' => 'Women', 'label' => 'Favourites' ),
            array( 'title' => 'Gradient Hoodie Blue',           'price' => '49', 'category' => 'Men',   'label' => 'Favourites' ),
        );

        foreach ( $products as $p ) {
            $pid = wp_insert_post( array(
                'post_title'  => sanitize_text_field( $p['title'] ),
                'post_type'   => 'buymore_product',
                'post_status' => 'publish',
            ) );
            if ( $pid && ! is_wp_error( $pid ) ) {
                update_post_meta( $pid, '_buymore_price',    sanitize_text_field( $p['price'] ) );
                update_post_meta( $pid, '_buymore_category', sanitize_text_field( $p['category'] ) );
                update_post_meta( $pid, '_buymore_label',    sanitize_text_field( $p['label'] ) );
            }
        }
    }

    public function enqueue_frontend_assets() {
        global $post;
        if ( ! is_a( $post, 'WP_Post' ) || ! has_shortcode( $post->post_content, 'buymore_dashboard' ) ) {
            return;
        }
        wp_enqueue_style(
            'buymore-frontend',
            BUYMORE_PLUGIN_URL . 'assets/css/frontend.css',
            array(),
            BUYMORE_VERSION
        );
        wp_enqueue_script(
            'buymore-frontend',
            BUYMORE_PLUGIN_URL . 'assets/js/frontend.js',
            array(),
            BUYMORE_VERSION,
            true
        );
        wp_localize_script( 'buymore-frontend', 'BuyMoreData', array(
            'ajaxurl' => admin_url( 'admin-ajax.php' ),
            'nonce'   => wp_create_nonce( 'buymore_nonce' ),
        ) );
    }

    public function load_textdomain() {
        load_plugin_textdomain( 'buymore-dashboard', false, dirname( BUYMORE_PLUGIN_BASENAME ) . '/languages' );
    }
}
