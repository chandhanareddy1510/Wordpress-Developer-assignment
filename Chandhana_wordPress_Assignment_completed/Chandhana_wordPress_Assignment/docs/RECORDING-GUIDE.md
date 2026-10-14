# Screen recording guide (about 4–5 minutes)

The assignment is **not processed without this recording**. Record with OBS Studio or Windows Game Bar (Win+G), save as `buymore-dashboard-demo.mp4`, upload (Google Drive / YouTube unlisted) and paste the link in the form. Afterwards play it once fully: voice audible, text readable, link opens in a private window.

Prepare: install the plugin, activate it, open the **BuyMore Dashboard** page, keep a second tab on wp-admin.

| Time | Show | Say (simple English) |
|---|---|---|
| 0:00 | Finished page, desktop | "This is my BuyMore dashboard, built as a custom WordPress plugin with PHP, HTML, CSS and JavaScript. No page builder is used." |
| 0:20 | Click Men/Women/All, search icon, Filters → sort by price, click a price, a heart | "Tabs, search, price sorting, cart and hearts all work." |
| 1:00 | Resize / dev tools to 768 then 375; open menu | "On tablet and mobile the sidebar becomes a menu and cards stack with no sideways scrolling." |
| 1:40 | VS Code: folder tree | "The main file loads classes: plugin, CPT, admin, renderer, shortcode, plus extras and images. Templates hold the HTML; assets hold the CSS and JavaScript." |
| 2:30 | Open `class-buymore-renderer.php`, `templates/dashboard.php` | "The renderer reads settings and products from WordPress and the template prints them, with escaping on every output." |
| 3:00 | wp-admin → BuyMore Products → edit sandal price to 42, Update; refresh page | "I change a price in the dashboard and the website updates without touching code." |
| 3:40 | BuyMore → Settings: change Orders Count and hero title; then Images & Links: choose a new hero image + a hero link | "Texts, images and button links are all managed here." |
| 4:30 | Frontend again | "Everything is editable from WordPress, responsive, and ready to use. Thank you." |
