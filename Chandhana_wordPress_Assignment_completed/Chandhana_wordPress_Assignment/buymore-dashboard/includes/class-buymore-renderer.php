<?php
/**
 * Frontend Renderer
 *
 * @package BuyMore_Dashboard
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

class BuyMore_Renderer {

    /**
     * Cached plugin settings.
     *
     * @var array
     */
    private $settings = array();

    public function __construct() {
        $defaults = array(
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
        );
        $this->settings = wp_parse_args( get_option( 'buymore_settings', array() ), $defaults );
    }

    private function s( $key ) {
        return esc_html( $this->settings[ $key ] ?? '' );
    }

    public function render_dashboard() {
        $products = $this->get_products();
        $picks    = array_filter( $products, fn( $p ) => $p['label'] === 'Our Picks' );
        $choices  = array_filter( $products, fn( $p ) => $p['label'] === 'Your Choice' );
        $favs     = array_filter( $products, fn( $p ) => $p['label'] === 'Favourites' );

        $picks   = array_values( $picks );
        $choices = array_values( $choices );
        $favs    = array_values( $favs );

        include BUYMORE_PLUGIN_DIR . 'templates/dashboard.php';
    }

    private function get_products() {
        $posts = get_posts( array(
            'post_type'   => 'buymore_product',
            'numberposts' => 20,
            'post_status' => 'publish',
            'orderby'     => 'date',
            'order'       => 'ASC',
        ) );

        $products = array();
        foreach ( $posts as $post ) {
            $thumb = has_post_thumbnail( $post->ID )
                ? get_the_post_thumbnail_url( $post->ID, 'medium' )
                : '';
            $products[] = array(
                'id'       => $post->ID,
                'title'    => $post->post_title,
                'price'    => get_post_meta( $post->ID, '_buymore_price', true ),
                'category' => get_post_meta( $post->ID, '_buymore_category', true ),
                'label'    => get_post_meta( $post->ID, '_buymore_label', true ),
                'image'    => $thumb,
                'excerpt'  => get_the_excerpt( $post ),
            );
        }
        return $products;
    }

    public function get_setting( $key ) {
        return $this->settings[ $key ] ?? '';
    }
}
