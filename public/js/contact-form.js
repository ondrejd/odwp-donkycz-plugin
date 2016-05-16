( function( $, plugin ) {

	/**
	 * Script for our contact form.
	 *
	 * @since 0.1
	 * @author Ondřej Doněk <ondrejd@gmail.com>
	 */

	var p = plugin.formPrefix;
console.log( p ); return;// XXX !!!
	jQuery( '#' + p + 'contact_form' ).submit( 
		function( event ) {
			console.log( "Contact Form Submitted", plugin );

			// Show spinner and disable submit button
			jQuery( '#' + p + 'spinner' ).css('visibility', 'visible' );
			jQuery( '#' + p + 'submit' ).prop('disabled', true );

			// Collect form data
			var formdata = $( this ).serializeArray();
			console.log( formdata );

			// Send them to the server
			jQuery.ajax( {
				url  : plugin.ajaxUrl,
				data : formdata,
				beforeSend : function( d ) {
					console.log( 'Before send', d );
				}
			} )
				.done( function( response, textStatus, jqXHR ) {
					console.log( 'AJAX done', textStatus, jqXHR, jqXHR.getAllResponseHeaders() );
					// this.processAJAXResponse( response );
				} )
				.fail( function( jqXHR, textStatus, errorThrown ) {
					console.log( 'AJAX failed', jqXHR.getAllResponseHeaders(), textStatus, errorThrown );
				} )
				.then( function( jqXHR, textStatus, errorThrown ) {
					console.log( 'AJAX after finished', jqXHR, textStatus, errorThrown );
				} );
			// ...

			event.preventDefault();
		}
	);
} )( jQuery, pluginObject || {} );