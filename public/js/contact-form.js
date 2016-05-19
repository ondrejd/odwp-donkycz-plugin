( function( $, plugin ) {

	/**
	 * Script for our contact form.
	 *
	 * @since 0.1
	 * @author Ondřej Doněk <ondrejd@gmail.com>
	 */

	var p = plugin.prefix;

	jQuery( '#' + p + 'contact_form' ).submit( 
		function( event ) {
			event.preventDefault();

			var data = jQuery( this ).serializeArray();
			data.push( { "name": "action", "value": "process_form_ajax" } );

			jQuery.post( {
				url  : plugin.url,
				data : data,
				beforeSend : function( d ) {
					jQuery( '#' + p + 'spinner' ).css( 'visibility', 'visible' );
					jQuery( '#' + p + 'submit' ).prop( 'disabled', true );
				}
			} )
				.done( function( response, textStatus, jqXHR ) {
					console.log( 'AJAX done', response, textStatus );
					// this.processAJAXResponse( response );
				} )
				.fail( function( jqXHR, textStatus, errorThrown ) {
					console.log( 'AJAX failed', textStatus, errorThrown );
				} )
				.then( function( jqXHR, textStatus, errorThrown ) {
					jQuery( '#' + p + 'spinner' ).css( 'visibility', 'collapse' );
					jQuery( '#' + p + 'submit' ).removeProp( 'disabled' );
				} );
		}
	);
} )( jQuery, pluginObject || {} );