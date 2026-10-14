=== BuyMore Dashboard ===
Contributors: chandhana
Tags: dashboard, fashion, ecommerce, custom, shortcode
Requires at least: 5.8
Tested up to: 6.7
Stable tag: 1.0.0
Requires PHP: 7.4
License: GPL-2.0-or-later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

A fashion e-commerce dashboard plugin for WordPress, implementing the BuyMore design reference.

== Description ==

BuyMore Dashboard is a custom WordPress plugin that renders a complete fashion e-commerce dashboard interface.
It demonstrates dynamic content handling through WordPress CMS, with no page builder required.

**Features:**
* Full-page dashboard rendered via [buymore_dashboard] shortcode
* Sidebar navigation with active states
* Product cards with category filter (All / Men / Women)
* Dynamic products via a custom post type (BuyMore Products)
* All text and labels editable from WordPress Admin > BuyMore > Settings
* Featured image support for products
* Fully responsive (desktop 1440px, tablet 768px, mobile 375px)
* Accessible markup with ARIA labels and keyboard navigation
* Vanilla CSS and JavaScript — no build step required

== Installation ==

1. Upload the `buymore-dashboard` folder to `/wp-content/plugins/`.
2. Activate the plugin in WordPress Admin > Plugins.
3. A page called **BuyMore Dashboard** is automatically created with the shortcode.
4. Navigate to that page to view the dashboard.

Alternatively, add `[buymore_dashboard]` to any page or post manually.

== Usage ==

**Change labels and text:**
WordPress Admin > BuyMore > Settings

**Manage products:**
WordPress Admin > BuyMore Products > Add New Product
Set a title, price, category (All / Men / Women), label (Our Picks / Your Choice / Favourites),
and upload a featured image.

**Display the dashboard:**
Add the shortcode `[buymore_dashboard]` to any page or post.

== Changelog ==

= 1.0.0 =
* Initial release.

== Upgrade Notice ==

= 1.0.0 =
Initial release.
