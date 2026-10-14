<?php
/**
 * Custom Post Types
 *
 * @package BuyMore_Dashboard
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

class BuyMore_CPT {

    private static $instance = null;

    public static function instance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        add_action( 'init', array( $this, 'register_post_types' ) );
    }

    public function register_post_types() {
        register_post_type( 'buymore_product', array(
            'labels'        => array(
                'name'               => __( 'Products', 'buymore-dashboard' ),
                'singular_name'      => __( 'Product', 'buymore-dashboard' ),
                'add_new'            => __( 'Add New Product', 'buymore-dashboard' ),
                'add_new_item'       => __( 'Add New Product', 'buymore-dashboard' ),
                'edit_item'          => __( 'Edit Product', 'buymore-dashboard' ),
                'new_item'           => __( 'New Product', 'buymore-dashboard' ),
                'view_item'          => __( 'View Product', 'buymore-dashboard' ),
                'search_items'       => __( 'Search Products', 'buymore-dashboard' ),
                'not_found'          => __( 'No products found', 'buymore-dashboard' ),
                'not_found_in_trash' => __( 'No products found in trash', 'buymore-dashboard' ),
                'menu_name'          => __( 'BuyMore Products', 'buymore-dashboard' ),
            ),
            'public'        => true,
            'show_in_menu'  => true,
            'menu_icon'     => 'dashicons-cart',
            'supports'      => array( 'title', 'thumbnail', 'excerpt' ),
            'has_archive'   => false,
            'rewrite'       => array( 'slug' => 'buymore-product' ),
            'show_in_rest'  => true,
        ) );
    }
}
