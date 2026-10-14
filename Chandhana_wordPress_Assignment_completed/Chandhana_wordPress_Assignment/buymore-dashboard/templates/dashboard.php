<?php
/**
 * Dashboard Template
 *
 * Variables available:
 *   $this      BuyMore_Renderer instance
 *   $products  All products
 *   $picks     Products labelled 'Our Picks'
 *   $choices   Products labelled 'Your Choice'
 *   $favs      Products labelled 'Favourites'
 *
 * @package BuyMore_Dashboard
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

$plugin_url = BUYMORE_PLUGIN_URL;
$s = $this->settings;

if ( ! function_exists( 'bm_img' ) ) {
function bm_img( $url, $fallback_class = '' ) {
    if ( $url ) {
        return '<img src="' . esc_url( $url ) . '" alt="" loading="lazy">';
    }
    return '<div class="bm-img-placeholder ' . esc_attr( $fallback_class ) . '"></div>';
}
}
?>
<div class="bm-wrapper" id="buymore-dashboard">
    <div class="bm-app-card">

        <!-- ═══════════════════════════════════
             SIDEBAR
        ═══════════════════════════════════════ -->
        <aside class="bm-sidebar" id="bm-sidebar" role="navigation" aria-label="<?php esc_attr_e( 'Main navigation', 'buymore-dashboard' ); ?>">
            <div class="bm-logo">
                <span class="bm-logo-text"><?php echo esc_html( $s['site_name'] ); ?></span>
            </div>

            <nav class="bm-nav">
                <ul>
                    <li>
                        <a href="#" class="bm-nav-link" data-section="popular">
                            <span class="bm-nav-icon">⚡</span>
                            <?php esc_html_e( 'Popular Products', 'buymore-dashboard' ); ?>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="bm-nav-link bm-nav-link--active" data-section="explore">
                            <span class="bm-nav-icon">🔵</span>
                            <?php esc_html_e( 'Explore New', 'buymore-dashboard' ); ?>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="bm-nav-link" data-section="clothing">
                            <span class="bm-nav-icon">👕</span>
                            <?php esc_html_e( 'Clothing and Shoes', 'buymore-dashboard' ); ?>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="bm-nav-link" data-section="gifts">
                            <span class="bm-nav-icon">🎁</span>
                            <?php esc_html_e( 'Gifts and Living', 'buymore-dashboard' ); ?>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="bm-nav-link" data-section="inspiration">
                            <span class="bm-nav-icon">💡</span>
                            <?php esc_html_e( 'Inspiration', 'buymore-dashboard' ); ?>
                        </a>
                    </li>
                </ul>
            </nav>

            <div class="bm-quick-actions">
                <p class="bm-section-label"><?php esc_html_e( 'Quick actions', 'buymore-dashboard' ); ?></p>
                <button class="bm-action-btn" type="button" id="bm-request-product">
                    <span>+</span> <?php esc_html_e( 'Request for product', 'buymore-dashboard' ); ?>
                </button>
                <button class="bm-action-btn" type="button" id="bm-add-member">
                    <span>+</span> <?php esc_html_e( 'Add member', 'buymore-dashboard' ); ?>
                </button>
            </div>

            <div class="bm-recent-orders">
                <p class="bm-section-label">
                    <?php esc_html_e( 'Last orders', 'buymore-dashboard' ); ?>
                    <strong><?php echo esc_html( $s['orders_count'] ); ?></strong>
                </p>
                <div class="bm-order-item">
                    <div class="bm-order-thumb bm-order-thumb--shoe"></div>
                    <span>DXC Nike...<a href="#" class="bm-view-order"><?php esc_html_e( 'view order', 'buymore-dashboard' ); ?></a></span>
                </div>
                <div class="bm-order-item">
                    <div class="bm-order-thumb bm-order-thumb--coat"></div>
                    <span>Outerwear...<a href="#" class="bm-view-order"><?php esc_html_e( 'view order', 'buymore-dashboard' ); ?></a></span>
                </div>
                <a href="#" class="bm-see-all"><?php esc_html_e( 'See all', 'buymore-dashboard' ); ?></a>
            </div>

            <button class="bm-logout-btn" type="button" id="bm-logout">
                <span class="bm-logout-icon">↩</span>
                <?php esc_html_e( 'Log out', 'buymore-dashboard' ); ?>
            </button>
        </aside>

        <!-- ═══════════════════════════════════
             MAIN CONTENT
        ═══════════════════════════════════════ -->
        <main class="bm-main" role="main">

            <!-- TOP BAR -->
            <header class="bm-topbar">
                <div class="bm-topbar-left">
                    <button class="bm-hamburger" id="bm-hamburger" aria-label="<?php esc_attr_e( 'Toggle navigation', 'buymore-dashboard' ); ?>" aria-expanded="false" aria-controls="bm-sidebar">
                        <span></span><span></span><span></span>
                    </button>
                    <div class="bm-orders-stat">
                        <span class="bm-orders-num"><?php echo esc_html( $s['orders_count'] ); ?></span>
                        <div class="bm-orders-meta">
                            <span><?php esc_html_e( 'Orders', 'buymore-dashboard' ); ?></span>
                            <span class="bm-orders-period"><?php echo esc_html( $s['orders_period'] ); ?></span>
                        </div>
                    </div>
                </div>

                <div class="bm-topbar-center">
                    <div class="bm-toggle-group" role="group" aria-label="<?php esc_attr_e( 'View toggle', 'buymore-dashboard' ); ?>">
                        <button class="bm-toggle-btn bm-toggle-btn--active" id="bm-view-dashboard" type="button">
                            <?php esc_html_e( 'Dashboard', 'buymore-dashboard' ); ?>
                        </button>
                        <button class="bm-toggle-btn" id="bm-view-website" type="button">
                            <?php esc_html_e( 'Website', 'buymore-dashboard' ); ?>
                        </button>
                    </div>
                </div>

                <div class="bm-topbar-right">
                    <button class="bm-cart-btn" type="button" aria-label="<?php esc_attr_e( 'Cart', 'buymore-dashboard' ); ?>">
                        <span class="bm-cart-icon">🛒</span>
                        <span><?php esc_html_e( 'Cart', 'buymore-dashboard' ); ?></span>
                    </button>
                    <div class="bm-user-avatars" aria-label="<?php esc_attr_e( 'Active users', 'buymore-dashboard' ); ?>">
                        <div class="bm-avatar bm-avatar--1"></div>
                        <div class="bm-avatar bm-avatar--2"></div>
                        <div class="bm-avatar bm-avatar--3"></div>
                        <span class="bm-avatar-more">+8</span>
                    </div>
                    <div class="bm-user-profile">
                        <div class="bm-user-avatar-main"></div>
                        <span class="bm-username"><?php echo esc_html( $s['username'] ); ?></span>
                    </div>
                </div>
            </header>

            <!-- EXPLORE SECTION HEADER -->
            <div class="bm-explore-header">
                <h1 class="bm-explore-title"><?php esc_html_e( 'Explore', 'buymore-dashboard' ); ?></h1>
                <div class="bm-filter-tabs" role="tablist" aria-label="<?php esc_attr_e( 'Product filters', 'buymore-dashboard' ); ?>">
                    <button class="bm-tab bm-tab--active" type="button" data-filter="all" role="tab" aria-selected="true">
                        <span class="bm-tab-icon">⊞</span> <?php esc_html_e( 'All', 'buymore-dashboard' ); ?>
                    </button>
                    <button class="bm-tab" type="button" data-filter="men" role="tab" aria-selected="false">
                        <span class="bm-tab-icon">♂</span> <?php esc_html_e( 'Men', 'buymore-dashboard' ); ?>
                    </button>
                    <button class="bm-tab" type="button" data-filter="women" role="tab" aria-selected="false">
                        <span class="bm-tab-icon">♀</span> <?php esc_html_e( 'Women', 'buymore-dashboard' ); ?>
                    </button>
                </div>
                <div class="bm-explore-actions">
                    <button class="bm-filters-btn" type="button" id="bm-filters-btn">
                        <?php esc_html_e( 'Filters', 'buymore-dashboard' ); ?>
                    </button>
                    <button class="bm-search-btn" type="button" aria-label="<?php esc_attr_e( 'Search', 'buymore-dashboard' ); ?>">
                        🔍
                    </button>
                </div>
            </div>

            <!-- PRODUCT GRID -->
            <div class="bm-grid">

                <!-- Column 1: Hero + Promo + Offers/Favourites -->
                <div class="bm-col bm-col--left">

                    <!-- Hero Banner -->
                    <div class="bm-card bm-card--hero" id="bm-hero">
                        <div class="bm-hero-content">
                            <h2 class="bm-hero-title"><?php echo esc_html( $s['hero_title'] ); ?></h2>
                            <button class="bm-btn bm-btn--hero" type="button"><?php echo esc_html( $s['hero_button'] ); ?></button>
                        </div>
                        <div class="bm-hero-figures">
                            <div class="bm-hero-fig bm-hero-fig--1"></div>
                            <div class="bm-hero-fig bm-hero-fig--2"></div>
                        </div>
                    </div>

                    <!-- Promo Card -->
                    <div class="bm-card bm-card--promo">
                        <div class="bm-promo-content">
                            <h2 class="bm-promo-title"><?php echo esc_html( $s['promo_title'] ); ?></h2>
                            <p class="bm-promo-sub"><?php echo esc_html( $s['promo_subtitle'] ); ?></p>
                        </div>
                        <div class="bm-promo-figure"></div>
                        <button class="bm-card-link" type="button" aria-label="<?php esc_attr_e( 'Open promo', 'buymore-dashboard' ); ?>">↗</button>
                    </div>

                    <!-- Bottom row: Offers + Favourites -->
                    <div class="bm-bottom-row">
                        <!-- Offers Card -->
                        <div class="bm-card bm-card--offers">
                            <div class="bm-offers-figure"></div>
                            <button class="bm-btn bm-btn--offers" type="button"><?php echo esc_html( $s['offers_button'] ); ?></button>
                            <button class="bm-heart-btn" type="button" aria-label="<?php esc_attr_e( 'Wishlist', 'buymore-dashboard' ); ?>">♡</button>
                        </div>

                        <!-- Favourites Card -->
                        <div class="bm-card bm-card--favs">
                            <div class="bm-favs-header">
                                <span class="bm-favs-title"><?php esc_html_e( 'Favourites', 'buymore-dashboard' ); ?></span>
                                <div class="bm-favs-nav">
                                    <button class="bm-fav-prev" type="button" aria-label="<?php esc_attr_e( 'Previous', 'buymore-dashboard' ); ?>">‹</button>
                                    <button class="bm-fav-next" type="button" aria-label="<?php esc_attr_e( 'Next', 'buymore-dashboard' ); ?>">›</button>
                                </div>
                            </div>
                            <div class="bm-favs-items">
                                <?php if ( ! empty( $favs ) ) : ?>
                                    <?php foreach ( $favs as $fav ) : ?>
                                        <div class="bm-fav-item" data-category="<?php echo esc_attr( strtolower( $fav['category'] ) ); ?>">
                                            <?php if ( $fav['image'] ) : ?>
                                                <img src="<?php echo esc_url( $fav['image'] ); ?>" alt="<?php echo esc_attr( $fav['title'] ); ?>" loading="lazy">
                                            <?php else : ?>
                                                <div class="bm-fav-placeholder"></div>
                                            <?php endif; ?>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                    <div class="bm-fav-item"><div class="bm-fav-placeholder bm-fav-placeholder--1"></div></div>
                                    <div class="bm-fav-item"><div class="bm-fav-placeholder bm-fav-placeholder--2"></div></div>
                                <?php endif; ?>
                            </div>
                            <button class="bm-btn bm-btn--see-all" type="button"><?php esc_html_e( 'See All', 'buymore-dashboard' ); ?></button>
                        </div>
                    </div>

                </div><!-- /bm-col--left -->

                <!-- Column 2: Product cards + Bold Fashion -->
                <div class="bm-col bm-col--right">

                    <?php foreach ( $picks as $product ) : ?>
                    <!-- Our Picks Product -->
                    <div class="bm-card bm-card--product" data-category="<?php echo esc_attr( strtolower( $product['category'] ) ); ?>">
                        <div class="bm-product-image">
                            <?php if ( $product['image'] ) : ?>
                                <img src="<?php echo esc_url( $product['image'] ); ?>" alt="<?php echo esc_attr( $product['title'] ); ?>" loading="lazy">
                            <?php else : ?>
                                <div class="bm-product-placeholder bm-product-placeholder--sandal"></div>
                            <?php endif; ?>
                            <button class="bm-heart-btn bm-heart-btn--product" type="button" aria-label="<?php esc_attr_e( 'Add to wishlist', 'buymore-dashboard' ); ?>">♡</button>
                            <div class="bm-color-swatches">
                                <span class="bm-swatch bm-swatch--pink"></span>
                                <span class="bm-swatch bm-swatch--yellow"></span>
                            </div>
                        </div>
                        <div class="bm-product-info">
                            <span class="bm-product-label"><?php echo esc_html( $product['label'] ); ?></span>
                            <h3 class="bm-product-title"><?php echo esc_html( $product['title'] ); ?></h3>
                        </div>
                        <button class="bm-price-btn" type="button">$<?php echo esc_html( $product['price'] ); ?></button>
                    </div>
                    <?php endforeach; ?>

                    <?php foreach ( $choices as $product ) : ?>
                    <!-- Your Choice Product -->
                    <div class="bm-card bm-card--product" data-category="<?php echo esc_attr( strtolower( $product['category'] ) ); ?>">
                        <div class="bm-product-image">
                            <?php if ( $product['image'] ) : ?>
                                <img src="<?php echo esc_url( $product['image'] ); ?>" alt="<?php echo esc_attr( $product['title'] ); ?>" loading="lazy">
                            <?php else : ?>
                                <div class="bm-product-placeholder bm-product-placeholder--jogger"></div>
                            <?php endif; ?>
                            <button class="bm-heart-btn bm-heart-btn--product" type="button" aria-label="<?php esc_attr_e( 'Add to wishlist', 'buymore-dashboard' ); ?>">♡</button>
                            <div class="bm-color-swatches">
                                <span class="bm-swatch bm-swatch--yellow"></span>
                                <span class="bm-swatch bm-swatch--black"></span>
                            </div>
                        </div>
                        <div class="bm-product-info">
                            <span class="bm-product-label"><?php echo esc_html( $product['label'] ); ?></span>
                            <h3 class="bm-product-title"><?php echo esc_html( $product['title'] ); ?></h3>
                        </div>
                        <button class="bm-price-btn" type="button">$<?php echo esc_html( $product['price'] ); ?></button>
                    </div>
                    <?php endforeach; ?>

                    <!-- Bold Fashion Card -->
                    <div class="bm-card bm-card--bold">
                        <div class="bm-bold-content">
                            <h2 class="bm-bold-title"><?php echo esc_html( $s['bold_title'] ); ?></h2>
                            <p class="bm-bold-sub"><?php echo esc_html( $s['bold_subtitle'] ); ?></p>
                        </div>
                        <div class="bm-bold-figure"></div>
                        <button class="bm-card-link" type="button" aria-label="<?php esc_attr_e( 'Open bold fashion', 'buymore-dashboard' ); ?>">↗</button>
                    </div>

                </div><!-- /bm-col--right -->

            </div><!-- /bm-grid -->

        </main><!-- /bm-main -->

    </div><!-- /bm-app-card -->
</div><!-- /bm-wrapper -->
