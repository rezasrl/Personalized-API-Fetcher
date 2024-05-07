( function( $ ) {
	'use strict';

	$( '#paf_preference_field_form' ).submit( function( e ) {
		e.preventDefault();
		const PafPreferenceField = $( '#paf_preference_field' ).val();

		$.ajax( {
			type: 'POST',
			url: personalized_api_fetcher_general_params.ajax_url,
			data: {
				action: 'save_paf_preference_field',
				paf_preference_field: PafPreferenceField,
			},
			success: function( response ) {
				if( response == 'success' ){
					$( '#paf_preference_field_message' ).html( '<div class="woocommerce-message">Preference field saved successfully.</div>' );

					return;
				}
				$( '#paf_preference_field_message' ).html( '<div class="woocommerce-error">Error saving Preference field.</div>' );
			}
		} );
	} );
} )( jQuery );
