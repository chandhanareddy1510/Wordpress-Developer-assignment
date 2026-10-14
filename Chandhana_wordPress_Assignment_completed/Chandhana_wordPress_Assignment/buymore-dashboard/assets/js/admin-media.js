/* BuyMore – Media Library pickers for the Images & Links screen. */
( function ( $ ) {
    'use strict';

    $( '.bm-img-row' ).each( function () {
        var $row   = $( this );
        var $input = $row.find( 'input[type="hidden"]' );
        var $img   = $row.find( 'img' );
        var frame;

        $row.find( '.bm-pick' ).on( 'click', function ( e ) {
            e.preventDefault();
            if ( ! frame ) {
                frame = wp.media( {
                    title: BuyMoreAdmin.title,
                    button: { text: BuyMoreAdmin.button },
                    library: { type: 'image' },
                    multiple: false
                } );
                frame.on( 'select', function () {
                    var att = frame.state().get( 'selection' ).first().toJSON();
                    $input.val( att.id );
                    $img.attr( 'src', ( att.sizes && att.sizes.medium ) ? att.sizes.medium.url : att.url );
                } );
            }
            frame.open();
        } );

        $row.find( '.bm-reset' ).on( 'click', function ( e ) {
            e.preventDefault();
            $input.val( 0 );
            $img.attr( 'src', $img.data( 'default' ) );
        } );
    } );
} )( jQuery );
