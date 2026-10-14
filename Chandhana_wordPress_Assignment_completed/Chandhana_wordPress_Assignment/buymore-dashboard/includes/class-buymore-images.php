<?php
/**
 * CMS-managed images and links.
 *
 * - Editable figure images (hero, promo, offers, bold, avatar) with bundled defaults.
 * - Editable call-to-action links.
 * - One-time import of the bundled product photos into the Media Library so the
 *   seeded products have real featured images that the admin can replace.
 *
 * @package BuyMore_Dashboard
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

class BuyMore_Images {

    const OPT_IMAGES = 'buymore_images';
    const OPT_LINKS  = 'buymore_links';
    const SEED_FLAG  = 'buymore_images_seeded';

    private static $instance = null;

    public static function instance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        add_action( 'init', array( $this, 'seed_product_images' ), 30 );
        add_action( 'admin_menu', array( $this, 'add_menu' ), 20 );
        add_action( 'admin_init', array( $this, 'register_settings' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin' ) );
    }

    /* ── Definitions ───────────────────────────── */

    /** Editable image slots: key => array( label, bundled default ). */
    public static function slots() {
        return array(
            'hero_left'  => array( __( 'Hero banner – left figure', 'buymore-dashboard' ),   'hero-left.jpg' ),
            'hero_right' => array( __( 'Hero banner – right figure', 'buymore-dashboard' ),  'hero-right.jpg' ),
            'promo'      => array( __( "Promo card (Winter's weekend) figure", 'buymore-dashboard' ), 'promo.jpg' ),
            'offers'     => array( __( 'Offers card photo', 'buymore-dashboard' ),           'offers.jpg' ),
            'bold'       => array( __( 'Bold Fashion card figure', 'buymore-dashboard' ),    'bold.jpg' ),
            'avatar'     => array( __( 'Top-bar user avatar', 'buymore-dashboard' ),         'avatar.jpg' ),
        );
    }

    /** Editable links: key => label. */
    public static function link_fields() {
        return array(
            'hero'    => __( 'Hero button link', 'buymore-dashboard' ),
            'promo'   => __( 'Promo card arrow link', 'buymore-dashboard' ),
            'offers'  => __( 'Offers button link', 'buymore-dashboard' ),
            'favs'    => __( 'Favourites "See All" link', 'buymore-dashboard' ),
            'bold'    => __( 'Bold Fashion arrow link', 'buymore-dashboard' ),
            'orders'  => __( 'Sidebar orders / "See all" link', 'buymore-dashboard' ),
            'website' => __( '"Website" toggle link', 'buymore-dashboard' ),
        );
    }

    private static function default_url( $file ) {
        return BUYMORE_PLUGIN_URL . 'assets/images/' . $file;
    }

    /** URL for a slot: chosen attachment, else the bundled default. */
    public static function url( $slot ) {
        $slots = self::slots();
        if ( ! isset( $slots[ $slot ] ) ) { return ''; }
        $saved = (array) get_option( self::OPT_IMAGES, array() );
        $id    = isset( $saved[ $slot ] ) ? absint( $saved[ $slot ] ) : 0;
        if ( $id ) {
            $url = wp_get_attachment_image_url( $id, 'large' );
            if ( $url ) { return $url; }
        }
        return self::default_url( $slots[ $slot ][1] );
    }

    public static function links() {
        $saved = (array) get_option( self::OPT_LINKS, array() );
        $out   = array();
        foreach ( array_keys( self::link_fields() ) as $key ) {
            $out[ $key ] = isset( $saved[ $key ] ) ? esc_url( $saved[ $key ] ) : '';
        }
        return $out;
    }

    /** CSS custom properties consumed by frontend-layout.css. */
    public static function css_variables() {
        $vars = array();
        foreach ( array_keys( self::slots() ) as $slot ) {
            $vars[] = '--bm-img-' . str_replace( '_', '-', $slot ) . ":url('" . str_replace( "'", '%27', esc_url_raw( self::url( $slot ) ) ) . "')";
        }
        $vars[] = "--bm-img-sandal:url('" . esc_url_raw( self::default_url( 'sandal.jpg' ) ) . "')";
        $vars[] = "--bm-img-jogger:url('" . esc_url_raw( self::default_url( 'jogger.jpg' ) ) . "')";
        $vars[] = "--bm-img-thumb-shoe:url('" . esc_url_raw( self::default_url( 'thumb-shoe.jpg' ) ) . "')";
        $vars[] = "--bm-img-thumb-coat:url('" . esc_url_raw( self::default_url( 'thumb-coat.jpg' ) ) . "')";
        return '.bm-wrapper{' . implode( ';', $vars ) . '}';
    }

    /* ── One-time import of default product photos ── */

    public function seed_product_images() {
        if ( get_option( self::SEED_FLAG ) || ! post_type_exists( 'buymore_product' ) ) { return; }

        $map = array(
            'WMX Rubber Zebra sandal'      => 'sandal.jpg',
            'Supper Skinny jogger in brown' => 'jogger.jpg',
            'Orange Turtleneck Sweater'    => 'sweater.jpg',
            'Gradient Hoodie Blue'         => 'hoodie.jpg',
        );
        $products = get_posts( array( 'post_type' => 'buymore_product', 'numberposts' => 50, 'post_status' => 'publish' ) );
        if ( empty( $products ) ) { return; }

        foreach ( $products as $product ) {
            if ( isset( $map[ $product->post_title ] ) && ! has_post_thumbnail( $product->ID ) ) {
                $att = $this->import_file( $map[ $product->post_title ], $product->ID, $product->post_title );
                if ( $att ) { set_post_thumbnail( $product->ID, $att ); }
            }
        }
        update_option( self::SEED_FLAG, 1 );
    }

    private function import_file( $file, $parent_id, $title ) {
        $src = BUYMORE_PLUGIN_DIR . 'assets/images/' . $file;
        $up  = wp_upload_dir();
        if ( ! file_exists( $src ) || ! empty( $up['error'] ) ) { return 0; }

        require_once ABSPATH . 'wp-admin/includes/image.php';

        $name = wp_unique_filename( $up['path'], 'buymore-' . $file );
        $dest = trailingslashit( $up['path'] ) . $name;
        if ( ! copy( $src, $dest ) ) { return 0; }

        $att = wp_insert_attachment( array(
            'post_mime_type' => 'image/jpeg',
            'post_title'     => sanitize_text_field( $title ),
            'post_status'    => 'inherit',
        ), $dest, $parent_id );
        if ( ! $att || is_wp_error( $att ) ) { return 0; }

        wp_update_attachment_metadata( $att, wp_generate_attachment_metadata( $att, $dest ) );
        update_post_meta( $att, '_wp_attachment_image_alt', sanitize_text_field( $title ) );
        return $att;
    }

    /* ── Admin screen ──────────────────────────── */

    public function add_menu() {
        add_submenu_page(
            'buymore-settings',
            __( 'Images & Links', 'buymore-dashboard' ),
            __( 'Images & Links', 'buymore-dashboard' ),
            'manage_options',
            'buymore-images',
            array( $this, 'render_page' )
        );
    }

    public function register_settings() {
        register_setting( 'buymore_images_group', self::OPT_IMAGES, array( 'sanitize_callback' => array( $this, 'sanitize_images' ) ) );
        register_setting( 'buymore_images_group', self::OPT_LINKS, array( 'sanitize_callback' => array( $this, 'sanitize_links' ) ) );
    }

    public function sanitize_images( $input ) {
        $clean = array();
        foreach ( array_keys( self::slots() ) as $slot ) {
            $id = isset( $input[ $slot ] ) ? absint( $input[ $slot ] ) : 0;
            $clean[ $slot ] = ( $id && wp_attachment_is_image( $id ) ) ? $id : 0;
        }
        return $clean;
    }

    public function sanitize_links( $input ) {
        $clean = array();
        foreach ( array_keys( self::link_fields() ) as $key ) {
            $clean[ $key ] = isset( $input[ $key ] ) ? esc_url_raw( trim( wp_unslash( $input[ $key ] ) ) ) : '';
        }
        return $clean;
    }

    public function enqueue_admin( $hook ) {
        // phpcs:ignore WordPress.Security.NonceVerification.Recommended
        if ( empty( $_GET['page'] ) || 'buymore-images' !== $_GET['page'] ) { return; }
        wp_enqueue_media();
        wp_enqueue_script( 'buymore-admin-media', BUYMORE_PLUGIN_URL . 'assets/js/admin-media.js', array( 'jquery' ), BUYMORE_VERSION, true );
        wp_localize_script( 'buymore-admin-media', 'BuyMoreAdmin', array(
            'title'  => __( 'Choose an image', 'buymore-dashboard' ),
            'button' => __( 'Use this image', 'buymore-dashboard' ),
        ) );
        wp_add_inline_style( 'wp-admin', '.bm-img-row{display:flex;gap:16px;align-items:center;padding:12px 0;border-bottom:1px solid #e5e5e5}.bm-img-row img{width:110px;height:80px;object-fit:cover;border-radius:8px;background:#f0f0f0}.bm-img-row .bm-img-info{flex:1}.bm-img-row .bm-img-info strong{display:block;margin-bottom:6px}' );
    }

    public function render_page() {
        if ( ! current_user_can( 'manage_options' ) ) { return; }
        $saved = (array) get_option( self::OPT_IMAGES, array() );
        $links = (array) get_option( self::OPT_LINKS, array() );
        ?>
        <div class="wrap">
            <h1><?php esc_html_e( 'BuyMore – Images & Links', 'buymore-dashboard' ); ?></h1>
            <p><?php esc_html_e( 'Choose the pictures shown on the dashboard cards and where the card buttons go. Product photos are set on each product (Featured Image). Leave an image on "Default" to use the bundled artwork.', 'buymore-dashboard' ); ?></p>
            <form method="post" action="options.php">
                <?php settings_fields( 'buymore_images_group' ); ?>

                <h2><?php esc_html_e( 'Card images', 'buymore-dashboard' ); ?></h2>
                <?php foreach ( self::slots() as $slot => $def ) :
                    $id = isset( $saved[ $slot ] ) ? absint( $saved[ $slot ] ) : 0;
                    ?>
                    <div class="bm-img-row" data-slot="<?php echo esc_attr( $slot ); ?>">
                        <img src="<?php echo esc_url( self::url( $slot ) ); ?>" alt="" data-default="<?php echo esc_url( self::default_url( $def[1] ) ); ?>">
                        <div class="bm-img-info">
                            <strong><?php echo esc_html( $def[0] ); ?></strong>
                            <input type="hidden" name="<?php echo esc_attr( self::OPT_IMAGES . '[' . $slot . ']' ); ?>" value="<?php echo esc_attr( $id ); ?>">
                            <button type="button" class="button bm-pick"><?php esc_html_e( 'Choose image', 'buymore-dashboard' ); ?></button>
                            <button type="button" class="button-link bm-reset"><?php esc_html_e( 'Use default', 'buymore-dashboard' ); ?></button>
                        </div>
                    </div>
                <?php endforeach; ?>

                <h2 style="margin-top:28px"><?php esc_html_e( 'Button links', 'buymore-dashboard' ); ?></h2>
                <table class="form-table" role="presentation">
                    <?php foreach ( self::link_fields() as $key => $label ) : ?>
                        <tr>
                            <th><label for="bm_link_<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $label ); ?></label></th>
                            <td><input type="url" class="regular-text" id="bm_link_<?php echo esc_attr( $key ); ?>" name="<?php echo esc_attr( self::OPT_LINKS . '[' . $key . ']' ); ?>" value="<?php echo esc_attr( isset( $links[ $key ] ) ? $links[ $key ] : '' ); ?>" placeholder="https://"></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
                <p class="description"><?php esc_html_e( 'Empty links scroll the visitor to the product list.', 'buymore-dashboard' ); ?></p>
                <?php submit_button(); ?>
            </form>
        </div>
        <?php
    }
}
