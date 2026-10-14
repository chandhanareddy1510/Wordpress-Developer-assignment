/**
 * BuyMore Dashboard - interaction completion layer.
 * Adds: product search, price sorting (Filters), cart counter, and CMS-driven
 * button links. Works alongside frontend.js without altering it.
 */
( function () {
    'use strict';

    var cfg   = window.BuyMoreExtras || { links: {}, i18n: {} };
    var links = cfg.links || {};
    var t     = cfg.i18n || {};

    function $( sel, ctx ) { return ( ctx || document ).querySelector( sel ); }
    function $$( sel, ctx ) { return Array.prototype.slice.call( ( ctx || document ).querySelectorAll( sel ) ); }

    var toastTimer;
    function toast( msg ) {
        var el = $( '.bm-toast-x' );
        if ( ! el ) {
            el = document.createElement( 'div' );
            el.className = 'bm-toast-x';
            el.setAttribute( 'role', 'status' );
            el.style.cssText = 'position:fixed;bottom:24px;left:50%;transform:translateX(-50%);background:#1a1a2e;color:#fff;padding:10px 20px;border-radius:50px;font:500 .875rem Inter,system-ui,sans-serif;z-index:99999;box-shadow:0 4px 16px rgba(0,0,0,.25);transition:opacity .3s;max-width:90vw';
            document.body.appendChild( el );
        }
        el.textContent = msg;
        el.style.opacity = '1';
        clearTimeout( toastTimer );
        toastTimer = setTimeout( function () { el.style.opacity = '0'; }, 2500 );
    }

    function go( key, root ) {
        if ( links[ key ] ) { window.location.href = links[ key ]; return; }
        var grid = $( '.bm-col--right', root );
        if ( grid ) {
            grid.scrollIntoView( { behavior: 'smooth', block: 'start' } );
            toast( t.browse || '' );
        }
    }

    function init() {
        var root = $( '#buymore-dashboard' );
        if ( ! root ) { return; }

        var col      = $( '.bm-col--right', root );
        var bold     = $( '.bm-card--bold', root );
        var products = $$( '.bm-card--product', root );
        var original = products.slice();

        /* ── Button links (CMS: BuyMore > Images & Links) ── */
        function bind( sel, key ) {
            $$( sel, root ).forEach( function ( el ) {
                el.addEventListener( 'click', function ( e ) { e.preventDefault(); go( key, root ); } );
            } );
        }
        bind( '.bm-btn--hero', 'hero' );
        bind( '.bm-card--promo .bm-card-link', 'promo' );
        bind( '.bm-btn--offers', 'offers' );
        bind( '.bm-btn--see-all', 'favs' );
        bind( '.bm-card--bold .bm-card-link', 'bold' );
        bind( '.bm-see-all', 'orders' );
        bind( '.bm-view-order', 'orders' );
        var site = $( '#bm-view-website' );
        if ( site && links.website ) {
            site.addEventListener( 'click', function () { window.location.href = links.website; } );
        }

        /* ── Cart ── */
        var cartCount = 0;
        var cartBtn   = $( '.bm-cart-btn', root );
        var cartLabel = cartBtn ? cartBtn.querySelector( 'span:last-child' ) : null;
        var cartBase  = cartLabel ? cartLabel.textContent : 'Cart';
        function updateCart() {
            if ( cartLabel ) { cartLabel.textContent = cartCount ? cartBase + ' (' + cartCount + ')' : cartBase; }
        }
        $$( '.bm-price-btn', root ).forEach( function ( btn ) {
            btn.addEventListener( 'click', function () {
                var card  = btn.closest( '.bm-card--product' );
                var title = card ? card.querySelector( '.bm-product-title' ).textContent : '';
                cartCount++;
                updateCart();
                toast( ( t.added || 'Added to cart:' ) + ' ' + title );
            } );
        } );
        if ( cartBtn ) {
            cartBtn.addEventListener( 'click', function () {
                toast( cartCount ? ( t.cartItems || 'Items in your cart:' ) + ' ' + cartCount : ( t.cartEmpty || 'Your cart is empty.' ) );
            } );
        }

        /* ── Search ── */
        var actions   = $( '.bm-explore-actions', root );
        var searchBtn = $( '.bm-search-btn', root );
        var filterBtn = $( '#bm-filters-btn', root );
        var input;
        var empty;

        function visibleCount() {
            return products.filter( function ( c ) {
                return ! c.classList.contains( 'bm-hidden' ) && ! c.classList.contains( 'bm-hidden-search' );
            } ).length;
        }
        function applySearch() {
            var q = input ? input.value.trim().toLowerCase() : '';
            products.forEach( function ( c ) {
                var text = ( c.textContent || '' ).toLowerCase();
                c.classList.toggle( 'bm-hidden-search', !! q && text.indexOf( q ) === -1 );
            } );
            if ( empty ) { empty.hidden = visibleCount() > 0; }
        }
        if ( actions && searchBtn && col ) {
            input = document.createElement( 'input' );
            input.type = 'search';
            input.className = 'bm-search-input';
            input.placeholder = t.search || 'Search products…';
            input.setAttribute( 'aria-label', t.search || 'Search products' );
            input.hidden = true;
            actions.insertBefore( input, searchBtn );

            empty = document.createElement( 'p' );
            empty.className = 'bm-no-results';
            empty.hidden = true;
            empty.textContent = t.noResults || '';
            col.insertBefore( empty, bold );

            input.addEventListener( 'input', applySearch );
            searchBtn.addEventListener( 'click', function () {
                input.hidden = ! input.hidden;
                searchBtn.setAttribute( 'aria-expanded', String( ! input.hidden ) );
                if ( ! input.hidden ) { input.focus(); } else { input.value = ''; applySearch(); }
            } );
            // Tab filter changes the visible set; keep the empty message in sync.
            $$( '.bm-tab', root ).forEach( function ( tab ) {
                tab.addEventListener( 'click', function () { setTimeout( applySearch, 0 ); } );
            } );
        }

        /* ── Filters button = sort menu ── */
        if ( filterBtn && col ) {
            var wrap = document.createElement( 'div' );
            wrap.className = 'bm-sort-wrap';
            filterBtn.parentNode.insertBefore( wrap, filterBtn );
            wrap.appendChild( filterBtn );

            var menu = document.createElement( 'div' );
            menu.className = 'bm-sort-menu';
            menu.setAttribute( 'role', 'menu' );
            menu.setAttribute( 'aria-label', t.sortLabel || 'Sort products' );
            menu.hidden = true;
            var opts = [
                [ 'default', t.sortDefault || 'Default order' ],
                [ 'asc', t.sortLow || 'Price: low to high' ],
                [ 'desc', t.sortHigh || 'Price: high to low' ]
            ];
            opts.forEach( function ( o, i ) {
                var b = document.createElement( 'button' );
                b.type = 'button';
                b.setAttribute( 'role', 'menuitemradio' );
                b.setAttribute( 'aria-checked', String( i === 0 ) );
                b.dataset.sort = o[ 0 ];
                b.textContent = o[ 1 ];
                menu.appendChild( b );
            } );
            wrap.appendChild( menu );
            filterBtn.setAttribute( 'aria-haspopup', 'menu' );
            filterBtn.setAttribute( 'aria-expanded', 'false' );

            function price( c ) {
                var el = c.querySelector( '.bm-price-btn' );
                return el ? parseFloat( el.textContent.replace( /[^0-9.]/g, '' ) ) || 0 : 0;
            }
            function closeMenu() { menu.hidden = true; filterBtn.setAttribute( 'aria-expanded', 'false' ); }

            filterBtn.addEventListener( 'click', function ( e ) {
                e.stopPropagation();
                menu.hidden = ! menu.hidden;
                filterBtn.setAttribute( 'aria-expanded', String( ! menu.hidden ) );
            } );
            menu.addEventListener( 'click', function ( e ) {
                var b = e.target.closest( 'button' );
                if ( ! b ) { return; }
                var mode = b.dataset.sort;
                var list = original.slice();
                if ( mode === 'asc' )  { list.sort( function ( a, c ) { return price( a ) - price( c ); } ); }
                if ( mode === 'desc' ) { list.sort( function ( a, c ) { return price( c ) - price( a ); } ); }
                list.forEach( function ( c ) { col.insertBefore( c, empty || bold ); } );
                $$( 'button', menu ).forEach( function ( x ) { x.setAttribute( 'aria-checked', String( x === b ) ); } );
                closeMenu();
            } );
            document.addEventListener( 'click', function ( e ) { if ( ! wrap.contains( e.target ) ) { closeMenu(); } } );
            document.addEventListener( 'keydown', function ( e ) { if ( e.key === 'Escape' ) { closeMenu(); } } );
        }
    }

    if ( document.readyState === 'loading' ) {
        document.addEventListener( 'DOMContentLoaded', init );
    } else {
        init();
    }
} )();
