/**
 * BuyMore Dashboard - Frontend JavaScript
 * Version: 1.0.0
 * Package: BuyMore_Dashboard
 */
( function () {
    'use strict';

    // ── DOM helpers ─────────────────────────────
    function $( sel, ctx ) { return ( ctx || document ).querySelector( sel ); }
    function $$( sel, ctx ) { return Array.from( ( ctx || document ).querySelectorAll( sel ) ); }

    // ── Init on DOM ready ────────────────────────
    document.addEventListener( 'DOMContentLoaded', function () {
        initSidebar();
        initNavLinks();
        initToggleGroup();
        initFilterTabs();
        initHeartButtons();
        initQuickActions();
        initFavNav();
    } );

    // ── Sidebar (mobile hamburger) ───────────────
    function initSidebar() {
        var sidebar   = $( '#bm-sidebar' );
        var hamburger = $( '#bm-hamburger' );
        var overlay   = createOverlay();

        if ( ! sidebar || ! hamburger ) { return; }

        hamburger.addEventListener( 'click', function () {
            var isOpen = sidebar.classList.toggle( 'bm-sidebar--open' );
            overlay.classList.toggle( 'bm-overlay--visible', isOpen );
            hamburger.setAttribute( 'aria-expanded', String( isOpen ) );
        } );

        overlay.addEventListener( 'click', closeSidebar );

        document.addEventListener( 'keydown', function ( e ) {
            if ( e.key === 'Escape' ) { closeSidebar(); }
        } );

        function closeSidebar() {
            sidebar.classList.remove( 'bm-sidebar--open' );
            overlay.classList.remove( 'bm-overlay--visible' );
            hamburger.setAttribute( 'aria-expanded', 'false' );
        }
    }

    function createOverlay() {
        var el = document.createElement( 'div' );
        el.className = 'bm-overlay';
        el.setAttribute( 'aria-hidden', 'true' );
        document.body.appendChild( el );
        return el;
    }

    // ── Sidebar nav links ────────────────────────
    function initNavLinks() {
        $$( '.bm-nav-link' ).forEach( function ( link ) {
            link.addEventListener( 'click', function ( e ) {
                e.preventDefault();
                $$( '.bm-nav-link' ).forEach( function ( l ) {
                    l.classList.remove( 'bm-nav-link--active' );
                } );
                this.classList.add( 'bm-nav-link--active' );
            } );
        } );
    }

    // ── Dashboard / Website toggle ───────────────
    function initToggleGroup() {
        var dashBtn = $( '#bm-view-dashboard' );
        var siteBtn = $( '#bm-view-website' );

        if ( ! dashBtn || ! siteBtn ) { return; }

        function activate( active, inactive ) {
            active.classList.add( 'bm-toggle-btn--active' );
            inactive.classList.remove( 'bm-toggle-btn--active' );
        }

        dashBtn.addEventListener( 'click', function () { activate( dashBtn, siteBtn ); } );
        siteBtn.addEventListener( 'click', function () { activate( siteBtn, dashBtn ); } );
    }

    // ── Product filter tabs ──────────────────────
    function initFilterTabs() {
        var tabs     = $$( '.bm-tab' );
        var products = $$( '.bm-card--product' );

        if ( ! tabs.length ) { return; }

        tabs.forEach( function ( tab ) {
            tab.addEventListener( 'click', function () {
                // Update active tab.
                tabs.forEach( function ( t ) {
                    t.classList.remove( 'bm-tab--active' );
                    t.setAttribute( 'aria-selected', 'false' );
                } );
                tab.classList.add( 'bm-tab--active' );
                tab.setAttribute( 'aria-selected', 'true' );

                // Filter products.
                var filter = tab.dataset.filter; // 'all' | 'men' | 'women'
                products.forEach( function ( card ) {
                    if ( filter === 'all' ) {
                        card.classList.remove( 'bm-hidden' );
                        return;
                    }
                    var cat = ( card.dataset.category || '' ).toLowerCase();
                    card.classList.toggle( 'bm-hidden', cat !== filter );
                } );

                // Also filter fav items.
                $$( '.bm-fav-item' ).forEach( function ( item ) {
                    if ( filter === 'all' ) {
                        item.classList.remove( 'bm-hidden' );
                        return;
                    }
                    var cat = ( item.dataset.category || '' ).toLowerCase();
                    item.classList.toggle( 'bm-hidden', cat !== filter );
                } );
            } );
        } );
    }

    // ── Heart / wishlist toggle ──────────────────
    function initHeartButtons() {
        $$( '.bm-heart-btn' ).forEach( function ( btn ) {
            btn.addEventListener( 'click', function () {
                var active = this.classList.toggle( 'bm-heart-btn--active' );
                this.textContent = active ? '♥' : '♡';
                this.style.color = active ? '#e53e3e' : '';
                this.style.background = active ? '#fff' : '';

                announceToSR( active ? 'Added to wishlist' : 'Removed from wishlist' );
            } );
        } );
    }

    // ── Quick Actions ────────────────────────────
    function initQuickActions() {
        var requestBtn = $( '#bm-request-product' );
        var memberBtn  = $( '#bm-add-member' );
        var logoutBtn  = $( '#bm-logout' );

        if ( requestBtn ) {
            requestBtn.addEventListener( 'click', function () {
                showToast( 'Feature coming soon: Request a product!' );
            } );
        }

        if ( memberBtn ) {
            memberBtn.addEventListener( 'click', function () {
                showToast( 'Feature coming soon: Add a member!' );
            } );
        }

        if ( logoutBtn ) {
            logoutBtn.addEventListener( 'click', function () {
                if ( window.confirm( 'Are you sure you want to log out?' ) ) {
                    showToast( 'Logging out...' );
                }
            } );
        }
    }

    // ── Favourites carousel nav ──────────────────
    function initFavNav() {
        var prevBtn   = $( '.bm-fav-prev' );
        var nextBtn   = $( '.bm-fav-next' );
        var container = $( '.bm-favs-items' );

        if ( ! container || ! prevBtn || ! nextBtn ) { return; }

        prevBtn.addEventListener( 'click', function () {
            container.scrollBy( { left: -120, behavior: 'smooth' } );
        } );

        nextBtn.addEventListener( 'click', function () {
            container.scrollBy( { left: 120, behavior: 'smooth' } );
        } );
    }

    // ── Toast notification ───────────────────────
    function showToast( message ) {
        var existing = $( '.bm-toast' );
        if ( existing ) { existing.remove(); }

        var toast = document.createElement( 'div' );
        toast.className = 'bm-toast';
        toast.textContent = message;
        toast.setAttribute( 'role', 'alert' );
        toast.setAttribute( 'aria-live', 'polite' );
        toast.style.cssText = [
            'position:fixed',
            'bottom:24px',
            'left:50%',
            'transform:translateX(-50%)',
            'background:#1a1a2e',
            'color:#fff',
            'padding:10px 20px',
            'border-radius:50px',
            'font-family:inherit',
            'font-size:0.875rem',
            'z-index:9999',
            'box-shadow:0 4px 16px rgba(0,0,0,0.25)',
            'opacity:0',
            'transition:opacity 0.3s',
        ].join(';');

        document.body.appendChild( toast );
        requestAnimationFrame( function () {
            toast.style.opacity = '1';
        } );

        setTimeout( function () {
            toast.style.opacity = '0';
            setTimeout( function () { toast.remove(); }, 300 );
        }, 2800 );
    }

    // ── Screen-reader announcement ───────────────
    function announceToSR( message ) {
        var live = $( '#bm-sr-live' );
        if ( ! live ) {
            live = document.createElement( 'span' );
            live.id = 'bm-sr-live';
            live.setAttribute( 'aria-live', 'polite' );
            live.className = 'screen-reader-text';
            document.body.appendChild( live );
        }
        live.textContent = message;
        setTimeout( function () { live.textContent = ''; }, 1000 );
    }

} )();
