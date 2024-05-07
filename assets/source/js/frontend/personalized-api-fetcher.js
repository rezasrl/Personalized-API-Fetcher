( function( $ ) {
	'use strict';

	$( document ).ready( function() {
		const $form = $( '#paf_preference_field_form' );
		const $messageContainer = $( '#paf_preference_field_message' );
		const $preferenceField = $( '#paf_preference_field' );

		$form.submit( function( e ) {
			e.preventDefault();

			const PafPreferenceField = $preferenceField.val();

			$.ajax( {
				type: 'POST',
				url: personalized_api_fetcher_general_params.ajax_url,
				data: {
					action: 'save_paf_preference_field',
					paf_preference_field: PafPreferenceField,
				},
				success: function( response ) {
					let message = '';
					if ( response === 'success' ) {
						message = '<div class="woocommerce-message">Preference field saved successfully.</div>';
					} else {
						message = '<div class="woocommerce-error">Error saving Preference field.</div>';
					}

					$messageContainer.html( message );
				}
			} );
		} );
	} );
} )( jQuery );