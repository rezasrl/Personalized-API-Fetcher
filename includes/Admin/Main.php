<?php
/**
 * Handle admin hooks.
 *
 * @class       Admin
 * @version     1.0.0
 * @package     Personalized_Api_Fetcher/Classes/
 */

namespace Personalized_Api_Fetcher\Admin;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Admin main class
 */
final class Main {

	/**
	 * Initialize hooks
	 *
	 * @return void
	 */
	public static function hooks() {

		Assets::hooks();

		add_action( 'current_screen', [ __CLASS__, 'conditional_includes' ] );
		add_action( 'admin_init', [ __CLASS__ , 'register_settings' ] );
	}


	/**
	 * Include admin files conditionally.
	 *
	 * @return void
	 */
	public static function conditional_includes() {

		$screen = get_current_screen();

		if ( ! $screen ) {
			return;
		}

		switch ( $screen->id ) {
			case 'dashboard':
			case 'options-permalink':
			case 'users':
			case 'user':
			case 'profile':
			case 'user-edit':
		}
	}

	public static function register_settings() {
		add_settings_section(
			'paf_settings_section',
			esc_html__( 'Custom API Settings', 'personalized-api-fetcher' ),
			[ __CLASS__ , 'settings_section_callback' ],
			'general'
		);

		add_settings_field(
			'paf_credentials_field',
			esc_html__( 'API Credentials', 'personalized-api-fetcher' ),
			[ __CLASS__ , 'credentials_field_callback' ],
			'general',
			'paf_settings_section'
		);

		register_setting( 'general', 'paf_credentials' );
	}

	public static function settings_section_callback() {
		echo '<p>' . esc_html__( 'Configure your API credentials and user preferences below.', 'personalized-api-fetcher' ) . '</p>';
	}

	public static function credentials_field_callback() {
		$api_credentials = get_option( 'paf_credentials' );
		echo '<div class="paf-setting-form">';
		echo '<input class="paf-input-text" type="text" name="paf_credentials[api_key]" value="' . esc_attr( $api_credentials['api_key'] ) . '" placeholder="' . esc_html__( 'API Key', 'personalized-api-fetcher' ) . '" />';
		echo '<input class="paf-input-text" type="text" name="paf_credentials[api_secret]" value="' . esc_attr( $api_credentials['api_secret'] ) . '" placeholder="' . esc_html__( 'API Secret', 'personalized-api-fetcher' ) . '" />';
		echo '</div>';
	}
}
