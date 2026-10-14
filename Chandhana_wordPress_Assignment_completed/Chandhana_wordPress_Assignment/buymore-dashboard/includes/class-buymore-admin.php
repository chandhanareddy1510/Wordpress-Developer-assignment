<?php
/**
 * Admin Settings
 *
 * @package BuyMore_Dashboard
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

class BuyMore_Admin {

    private static $instance = null;

    public static function instance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        add_action( 'admin_menu', array( $this, 'add_menu' ) );
        add_action( 'admin_init', array( $this, 'register_settings' ) );
        add_action( 'add_meta_boxes', array( $this, 'add_product_meta_boxes' ) );
        add_action( 'save_post_buymore_product', array( $this, 'save_product_meta' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_assets' ) );
    }

    public function add_menu() {
        add_menu_page(
            __( 'BuyMore Settings', 'buymore-dashboard' ),
            __( 'BuyMore', 'buymore-dashboard' ),
            'manage_options',
            'buymore-settings',
            array( $this, 'settings_page' ),
            'dashicons-store',
            30
        );
    }

    public function register_settings() {
        register_setting( 'buymore_settings_group', 'buymore_settings', array(
            'sanitize_callback' => array( $this, 'sanitize_settings' ),
        ) );
    }

    public function sanitize_settings( $input ) {
        $clean = array();
        $text_fields = array(
            'site_name', 'orders_count', 'orders_period', 'username',
            'hero_title', 'hero_button', 'promo_title', 'promo_subtitle',
            'offers_button', 'bold_title', 'bold_subtitle',
        );
        foreach ( $text_fields as $field ) {
            $clean[ $field ] = isset( $input[ $field ] ) ? sanitize_text_field( $input[ $field ] ) : '';
        }
        return $clean;
    }

    public function settings_page() {
        if ( ! current_user_can( 'manage_options' ) ) { return; }
        $settings = get_option( 'buymore_settings', array() );
        ?>
        <div class="wrap">
            <h1><?php esc_html_e( 'BuyMore Dashboard Settings', 'buymore-dashboard' ); ?></h1>
            <form method="post" action="options.php">
                <?php settings_fields( 'buymore_settings_group' ); ?>
                <table class="form-table">
                    <tr>
                        <th><label for="site_name"><?php esc_html_e( 'Site Name', 'buymore-dashboard' ); ?></label></th>
                        <td><input type="text" id="site_name" name="buymore_settings[site_name]" value="<?php echo esc_attr( $settings['site_name'] ?? 'BuyMore' ); ?>" class="regular-text" /></td>
                    </tr>
                    <tr>
                        <th><label for="orders_count"><?php esc_html_e( 'Orders Count', 'buymore-dashboard' ); ?></label></th>
                        <td><input type="text" id="orders_count" name="buymore_settings[orders_count]" value="<?php echo esc_attr( $settings['orders_count'] ?? '37' ); ?>" class="small-text" /></td>
                    </tr>
                    <tr>
                        <th><label for="orders_period"><?php esc_html_e( 'Orders Period', 'buymore-dashboard' ); ?></label></th>
                        <td><input type="text" id="orders_period" name="buymore_settings[orders_period]" value="<?php echo esc_attr( $settings['orders_period'] ?? 'Last 7 days' ); ?>" class="regular-text" /></td>
                    </tr>
                    <tr>
                        <th><label for="username"><?php esc_html_e( 'Username', 'buymore-dashboard' ); ?></label></th>
                        <td><input type="text" id="username" name="buymore_settings[username]" value="<?php echo esc_attr( $settings['username'] ?? 'Ryana' ); ?>" class="regular-text" /></td>
                    </tr>
                    <tr>
                        <th><label for="hero_title"><?php esc_html_e( 'Hero Banner Title', 'buymore-dashboard' ); ?></label></th>
                        <td><input type="text" id="hero_title" name="buymore_settings[hero_title]" value="<?php echo esc_attr( $settings['hero_title'] ?? 'GET UP TO 50% OFF' ); ?>" class="regular-text" /></td>
                    </tr>
                    <tr>
                        <th><label for="hero_button"><?php esc_html_e( 'Hero Button Text', 'buymore-dashboard' ); ?></label></th>
                        <td><input type="text" id="hero_button" name="buymore_settings[hero_button]" value="<?php echo esc_attr( $settings['hero_button'] ?? 'Get Discount' ); ?>" class="regular-text" /></td>
                    </tr>
                    <tr>
                        <th><label for="promo_title"><?php esc_html_e( 'Promo Card Title', 'buymore-dashboard' ); ?></label></th>
                        <td><input type="text" id="promo_title" name="buymore_settings[promo_title]" value="<?php echo esc_attr( $settings['promo_title'] ?? "Winter's weekend" ); ?>" class="regular-text" /></td>
                    </tr>
                    <tr>
                        <th><label for="promo_subtitle"><?php esc_html_e( 'Promo Card Subtitle', 'buymore-dashboard' ); ?></label></th>
                        <td><input type="text" id="promo_subtitle" name="buymore_settings[promo_subtitle]" value="<?php echo esc_attr( $settings['promo_subtitle'] ?? 'keep it casual' ); ?>" class="regular-text" /></td>
                    </tr>
                    <tr>
                        <th><label for="offers_button"><?php esc_html_e( 'Offers Button Text', 'buymore-dashboard' ); ?></label></th>
                        <td><input type="text" id="offers_button" name="buymore_settings[offers_button]" value="<?php echo esc_attr( $settings['offers_button'] ?? 'Avail Offers' ); ?>" class="regular-text" /></td>
                    </tr>
                    <tr>
                        <th><label for="bold_title"><?php esc_html_e( 'Bold Fashion Title', 'buymore-dashboard' ); ?></label></th>
                        <td><input type="text" id="bold_title" name="buymore_settings[bold_title]" value="<?php echo esc_attr( $settings['bold_title'] ?? 'Bring Bold Fashion' ); ?>" class="regular-text" /></td>
                    </tr>
                    <tr>
                        <th><label for="bold_subtitle"><?php esc_html_e( 'Bold Fashion Subtitle', 'buymore-dashboard' ); ?></label></th>
                        <td><input type="text" id="bold_subtitle" name="buymore_settings[bold_subtitle]" value="<?php echo esc_attr( $settings['bold_subtitle'] ?? 'Layers on Layers' ); ?>" class="regular-text" /></td>
                    </tr>
                </table>
                <?php submit_button(); ?>
            </form>
            <hr>
            <h2><?php esc_html_e( 'Quick Instructions', 'buymore-dashboard' ); ?></h2>
            <ul>
                <li><?php esc_html_e( 'Use the shortcode [buymore_dashboard] on any page to display the dashboard.', 'buymore-dashboard' ); ?></li>
                <li><?php esc_html_e( 'Manage products under BuyMore Products in the sidebar.', 'buymore-dashboard' ); ?></li>
                <li><?php esc_html_e( 'Set a featured image on each product to display product photos.', 'buymore-dashboard' ); ?></li>
            </ul>
        </div>
        <?php
    }

    public function add_product_meta_boxes() {
        add_meta_box(
            'buymore_product_details',
            __( 'Product Details', 'buymore-dashboard' ),
            array( $this, 'render_product_meta_box' ),
            'buymore_product',
            'normal',
            'high'
        );
    }

    public function render_product_meta_box( $post ) {
        wp_nonce_field( 'buymore_save_product', 'buymore_product_nonce' );
        $price    = get_post_meta( $post->ID, '_buymore_price', true );
        $category = get_post_meta( $post->ID, '_buymore_category', true );
        $label    = get_post_meta( $post->ID, '_buymore_label', true );
        ?>
        <table class="form-table">
            <tr>
                <th><label for="buymore_price"><?php esc_html_e( 'Price ($)', 'buymore-dashboard' ); ?></label></th>
                <td><input type="number" id="buymore_price" name="buymore_price" value="<?php echo esc_attr( $price ); ?>" min="0" step="0.01" style="width:100px;" /></td>
            </tr>
            <tr>
                <th><label for="buymore_category"><?php esc_html_e( 'Category', 'buymore-dashboard' ); ?></label></th>
                <td>
                    <select id="buymore_category" name="buymore_category">
                        <option value="All"   <?php selected( $category, 'All' ); ?>><?php esc_html_e( 'All', 'buymore-dashboard' ); ?></option>
                        <option value="Men"   <?php selected( $category, 'Men' ); ?>><?php esc_html_e( 'Men', 'buymore-dashboard' ); ?></option>
                        <option value="Women" <?php selected( $category, 'Women' ); ?>><?php esc_html_e( 'Women', 'buymore-dashboard' ); ?></option>
                    </select>
                </td>
            </tr>
            <tr>
                <th><label for="buymore_label"><?php esc_html_e( 'Label', 'buymore-dashboard' ); ?></label></th>
                <td>
                    <select id="buymore_label" name="buymore_label">
                        <option value="Our Picks"   <?php selected( $label, 'Our Picks' ); ?>><?php esc_html_e( 'Our Picks', 'buymore-dashboard' ); ?></option>
                        <option value="Your Choice" <?php selected( $label, 'Your Choice' ); ?>><?php esc_html_e( 'Your Choice', 'buymore-dashboard' ); ?></option>
                        <option value="Favourites"  <?php selected( $label, 'Favourites' ); ?>><?php esc_html_e( 'Favourites', 'buymore-dashboard' ); ?></option>
                    </select>
                </td>
            </tr>
        </table>
        <?php
    }

    public function save_product_meta( $post_id ) {
        if ( ! isset( $_POST['buymore_product_nonce'] ) ) { return; }
        if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['buymore_product_nonce'] ) ), 'buymore_save_product' ) ) { return; }
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) { return; }
        if ( ! current_user_can( 'edit_post', $post_id ) ) { return; }

        if ( isset( $_POST['buymore_price'] ) ) {
            update_post_meta( $post_id, '_buymore_price', sanitize_text_field( wp_unslash( $_POST['buymore_price'] ) ) );
        }
        if ( isset( $_POST['buymore_category'] ) ) {
            update_post_meta( $post_id, '_buymore_category', sanitize_text_field( wp_unslash( $_POST['buymore_category'] ) ) );
        }
        if ( isset( $_POST['buymore_label'] ) ) {
            update_post_meta( $post_id, '_buymore_label', sanitize_text_field( wp_unslash( $_POST['buymore_label'] ) ) );
        }
    }

    public function enqueue_admin_assets( $hook ) {
        // Only load on our settings page or buymore_product CPT.
        global $post_type;
        if ( 'buymore_product' !== $post_type && 'toplevel_page_buymore-settings' !== $hook ) {
            return;
        }
    }
}
