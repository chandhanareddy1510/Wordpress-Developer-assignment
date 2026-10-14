# BuyMore Dashboard — WordPress Plugin

A custom WordPress plugin that implements the **BuyMore** fashion e-commerce dashboard design.  
Built with PHP, HTML, CSS, and JavaScript using WordPress APIs. No page builder used.

---

## Features

| Feature | Details |
|---------|---------|
| Shortcode | `[buymore_dashboard]` — drop on any page |
| Custom Post Type | **BuyMore Products** — managed from the WP Dashboard |
| CMS Settings | Admin > BuyMore — change all labels, counts, and text |
| Responsive | Desktop (1440px), Tablet (768px), Mobile (375px) |
| Dynamic Content | Products, prices, labels, and banners all CMS-editable |
| Accessibility | ARIA labels, keyboard navigation, visible focus states |
| No Dependencies | Vanilla CSS + JS; no jQuery, no Tailwind, no builders |

---

## Technology Stack

- **PHP 7.4+** — Plugin architecture, WordPress APIs, custom post type
- **HTML5** — Semantic markup, ARIA attributes
- **CSS3** — CSS custom properties, Grid, Flexbox, responsive media queries
- **JavaScript (ES5-compatible)** — IIFE pattern, DOM manipulation, filter logic
- **WordPress APIs** — `register_post_type`, `add_shortcode`, `wp_enqueue_*`, `get_option`, `update_option`

---

## Project Structure

```
buymore-dashboard/
├── buymore-dashboard.php          # Plugin entry point (header + bootstrap)
├── includes/
│   ├── class-buymore-plugin.php   # Main singleton class (hooks, assets, activation)
│   ├── class-buymore-cpt.php      # Custom post type: buymore_product
│   ├── class-buymore-admin.php    # Admin settings page + product meta boxes
│   ├── class-buymore-renderer.php # Data fetching + template loading
│   └── class-buymore-shortcode.php# [buymore_dashboard] shortcode registration
├── templates/
│   └── dashboard.php              # Full dashboard HTML template
├── assets/
│   ├── css/
│   │   └── frontend.css           # All styles (tokens, components, responsive)
│   └── js/
│       └── frontend.js            # Interactions (sidebar, tabs, filters, toasts)
├── readme.txt                     # WordPress.org readme format
└── README.md                      # This file
```

---

## Prerequisites

- WordPress 5.8 or higher
- PHP 7.4 or higher
- Any WordPress theme (plugin is self-contained)

---

## Installation

1. Copy the `buymore-dashboard` folder to `wp-content/plugins/`
2. In **WordPress Admin → Plugins**, activate **BuyMore Dashboard**
3. On activation the plugin:
   - Registers the `buymore_product` custom post type
   - Creates a page called **BuyMore Dashboard** with the shortcode already in it
   - Seeds four sample products with placeholder data
   - Stores default settings in `wp_options`

---

## Displaying the Dashboard

The dashboard is displayed wherever the shortcode is placed:

```
[buymore_dashboard]
```

After activation, visit the auto-created **BuyMore Dashboard** page.  
Or add the shortcode to any existing page.

---

## Managing Dynamic Content

### Settings (text labels)

**WordPress Admin → BuyMore** (menu icon: store)

| Field | Controls |
|-------|----------|
| Site Name | Sidebar logo text |
| Orders Count | Topbar stat + sidebar recent orders count |
| Orders Period | "Last 7 days" subtitle |
| Username | Top-right username |
| Hero Banner Title | "GET UP TO 50% OFF" |
| Hero Button Text | "Get Discount" |
| Promo Card Title | "Winter's weekend" |
| Promo Card Subtitle | "keep it casual" |
| Offers Button Text | "Avail Offers" |
| Bold Fashion Title | "Bring Bold Fashion" |
| Bold Fashion Subtitle | "Layers on Layers" |

Save → refresh the frontend page to see changes.

### Products

**WordPress Admin → BuyMore Products → Add New**

Each product has:
- **Title** — product name shown on card
- **Featured Image** — product photo (set via media library)
- **Price** — numeric price in dollars
- **Category** — All / Men / Women (used by filter tabs)
- **Label** — Our Picks / Your Choice / Favourites (controls which card the product appears in)

---

## Responsive Breakpoints

| Breakpoint | Viewport | Layout |
|-----------|---------|--------|
| Desktop | ≥ 1025px | Sidebar + two-column product grid |
| Tablet | 769–1024px | Sidebar visible, single-column grid with 2-col right section |
| Mobile | ≤ 768px | Hamburger menu, full-width stacked cards |
| Small | ≤ 480px | Condensed typography and spacing |

---

## Evaluation Criteria Coverage

| Criterion | Implementation |
|-----------|---------------|
| Design Accuracy | Matches BuyMore reference image layout, colors, and typography |
| Responsive Design | CSS Grid + Flexbox + 4 media query breakpoints |
| Dynamic Content Handling | Custom post type, wp_options, template rendering |
| CMS Usability | Settings page + product meta boxes, no PHP editing needed |
| Code Structure & Cleanliness | PSR-style PHP, BEM-style CSS, IIFE JS, no duplication |
| Technical Explanation | Screen recording narration script in README |

---

## Screen Recording Narration Script

**Duration:** ~4 minutes

### 1. Introduction (0:00–0:20)
> "This is the BuyMore Dashboard — a custom WordPress plugin implementing the reference design.
> I'll show you the live page, responsive behavior, the code structure, and how content is managed dynamically."

### 2. Live Desktop View (0:20–0:50)
> Open the BuyMore Dashboard page at 1440px.
> "On desktop you can see the sidebar with navigation, the topbar with order stats and user info,
> and the product grid with a hero banner, promotional cards, and individual product cards."
> Click the filter tabs (All, Men, Women) to demonstrate filtering.

### 3. Tablet View (0:50–1:15)
> Resize browser to 768px.
> "On tablet the layout collapses to a single-column grid. The product cards reflow to a 2-column right panel."

### 4. Mobile View (1:15–1:45)
> Resize to 375px.
> "On mobile, the sidebar becomes a slide-in drawer accessed via the hamburger menu. All cards stack vertically."
> Click hamburger to show sidebar opening.

### 5. Plugin Structure (1:45–2:20)
> Open VS Code or file manager showing buymore-dashboard folder.
> "The plugin follows a clean MVC-like structure:
> - buymore-dashboard.php is the entry point
> - includes/ contains PHP classes for the CPT, admin settings, renderer, and shortcode
> - templates/ has the HTML dashboard template
> - assets/ contains scoped CSS and vanilla JS"

### 6. Dynamic Content — Settings (2:20–3:00)
> Go to WordPress Admin → BuyMore → Settings.
> "I'll change the Orders Count from 37 to 42 and update the Hero Title."
> Save, go back to the frontend page.
> "The topbar now shows 42 orders and the new banner text — all changed through the CMS."

### 7. Dynamic Content — Products (3:00–3:40)
> Go to BuyMore Products → Add New.
> "I'll add a new product, set the price, category to Women, label to Our Picks, and upload a featured image."
> Publish. Go to frontend page, filter to Women.
> "The new product card appears immediately in the Women filter."

### 8. Wrap Up (3:40–4:00)
> "The plugin is installable on any WordPress site, deactivates cleanly, and requires no external dependencies.
> All content is managed through the WordPress dashboard without touching any code."

---

## Known Limitations

- Hero and promo card background illustrations are CSS-generated gradients (placeholder for actual product images).
  To add real images: upload to the media library and use the URL in custom CSS overrides or future template options.
- The cart button and order links are UI demonstrations; full e-commerce functionality is out of scope.
- Tested on WordPress 6.x. Older versions may behave differently with arrow functions in PHP if PHP < 7.4.

---

## Screen Recording Instructions

Use **OBS Studio** or Windows built-in **Xbox Game Bar** (Win + G):

1. Open the BuyMore Dashboard page in your browser.
2. Start recording.
3. Follow the narration script above.
4. Save the recording as `buymore-dashboard-demo.mp4`.
5. Verify playback before submission.

---

## Update — Completion Layer (v1.0.1 additions)

The original files were kept as they were; the work below was **added on top**:

| Added file | Purpose |
|-----------|---------|
| `assets/css/frontend-layout.css` | Full-width page, reference-accurate grid (tall product cards, real imagery), tablet/mobile refinements |
| `assets/js/frontend-extras.js` | Working search, Filters (price sort), cart counter, CMS-driven button links |
| `includes/class-buymore-extras.php` | Loads the above, adds a `bm-page` body class, registers the product post type early |
| `includes/class-buymore-images.php` | **BuyMore → Images & Links** screen (Media Library pickers + button links) and one-time import of default product photos |
| `assets/js/admin-media.js` | Media Library picker for that screen |
| `assets/images/` | Default artwork cropped from the assignment's reference image (replaceable in the CMS) |

Two small fixes to existing code (behaviour otherwise unchanged): one `require_once` line in `class-buymore-plugin.php`, and a `function_exists` guard around `bm_img()` in `templates/dashboard.php` (rendering the shortcode twice used to be a PHP fatal).
The "Known Limitations" about gradient placeholders above are now resolved: card images are real photos and editable from **BuyMore → Images & Links**.
