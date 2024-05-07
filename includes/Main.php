<?php
/**
 * Main class.
 *
 * @package  Personalized_Api_Fetcher
 * @version  1.0.0
 */

namespace Personalized_Api_Fetcher;

use Personalized_Api_Fetcher\Admin\Main as Admin;
use Personalized_Api_Fetcher\Front\Main as Front;


/**
 * Base Plugin class holding generic functionality
 */
final class Main {

	/**
	 * Set the minimum required versions for the plugin.
	 */
	const PLUGIN_REQUIREMENTS = array(
		'php_version' => '7.3',
		'wp_version'  => '5.6',
		'wc_version'  => '5.3',
	);


	/**
	 * Constructor
	 */
	public static function bootstrap() {

		register_activation_hook( PLUGIN_FILE, array( Install::class, 'install' ) );

		add_action( 'plugins_loaded', array( __CLASS__, 'load' ) );

		add_action( 'init', array( __CLASS__, 'init' ) );

		// Perform other actions when plugin is loaded.
		do_action( 'personalized_api_fetcher_loaded' );
	}


	/**
	 * Cloning is forbidden.
	 *
	 * @since 1.0.0
	 */
	public function __clone() {
		_doing_it_wrong( __FUNCTION__, esc_html__( 'Cheatin&#8217; huh?', 'personalized-api-fetcher' ), '1.0.0' );
	}


	/**
	 * Unserializing instances of this class is forbidden.
	 *
	 * @since 1.0.0
	 */
	public function __wakeup() {
		_doing_it_wrong( __FUNCTION__, esc_html__( 'Cheatin&#8217; huh?', 'personalized-api-fetcher' ), '1.0.0' );
	}


	/**
	 * Include plugins files and hook into actions and filters.
	 *
	 * @since  1.0.0
	 */
	public static function load() {

		if ( ! self::check_plugin_requirements() ) {
			return;
		}

		if ( Utils::is_request( 'admin' ) ) {
			Admin::hooks();
		}

		if ( Utils::is_request( 'frontend' ) ) {
			Front::hooks();
		}

		// Common includes.
		Block::hooks();

		Customizations\ACF::hooks();

		// Set up localisation.
		self::load_plugin_textdomain();

		// Init action.
		do_action( 'personalized_api_fetcher_loaded' );
	}


	/**
	 * Method called by init hook
	 *
	 * @return void
	 */
	public static function init() {

		// Before init action.
		do_action( 'before_personalized_api_fetcher_init' );

		define( 'PAF_MY_ACCOUNT_CUSTOM_ENDPOINT', 'tab' );

		self::add_user_preference_endpoint();

		add_filter( 'query_vars', [ __CLASS__, 'user_preference_query_vars' ], 0 );
		add_filter( 'woocommerce_account_menu_items', [ __CLASS__, 'add_user_preference_link_my_account' ] );
		add_action( 'woocommerce_account_paf-user-preferences_endpoint', [ __CLASS__, 'user_preference_content' ] );

		add_action( 'wp_ajax_save_paf_preference_field', [ __CLASS__, 'save_preference_field_data' ] );
		add_action( 'wp_ajax_nopriv_save_paf_preference_field', [ __CLASS__, 'save_preference_field_data' ] );

		// After init action.
		do_action( 'personalized_api_fetcher_init' );
	}


	/**
	 * Checks all plugin requirements. If run in admin context also adds a notice.
	 *
	 * @return boolean
	 */
	private static function check_plugin_requirements() {

		$errors = array();
		global $wp_version;

		if ( ! version_compare( PHP_VERSION, self::PLUGIN_REQUIREMENTS['php_version'], '>=' ) ) {
			/* Translators: The minimum PHP version */
			$errors[] = sprintf( esc_html__( 'Personalized API Fetcher requires a minimum PHP version of %s.', 'personalized-api-fetcher' ), self::PLUGIN_REQUIREMENTS['php_version'] );
		}

		if ( ! version_compare( $wp_version, self::PLUGIN_REQUIREMENTS['wp_version'], '>=' ) ) {
			/* Translators: The minimum WP version */
			$errors[] = sprintf( esc_html__( 'Personalized API Fetcher requires a minimum WordPress version of %s.', 'personalized-api-fetcher' ), self::PLUGIN_REQUIREMENTS['wp_version'] );
		}

		if ( isset( self::PLUGIN_REQUIREMENTS['wc_version'] ) && ( ! defined( 'WC_VERSION' ) || ! version_compare( WC_VERSION, self::PLUGIN_REQUIREMENTS['wc_version'], '>=' ) ) ) {
			/* Translators: The minimum WC version */
			$errors[] = sprintf( esc_html__( 'Personalized API Fetcher requires a minimum WooCommerce version of %s.', 'personalized-api-fetcher' ), self::PLUGIN_REQUIREMENTS['wc_version'] );
		}

		if ( empty( $errors ) ) {
			return true;
		}

		if ( Utils::is_request( 'admin' ) ) {

			add_action(
				'admin_notices',
				function() use ( $errors ) {
					?>
					<div class="notice notice-error">
						<?php
						foreach ( $errors as $error ) {
							echo '<p>' . esc_html( $error ) . '</p>';
						}
						?>
					</div>
					<?php
				}
			);

			return;
		}

		return false;
	}


	/**
	 * Load Localisation files.
	 *
	 * Note: the first-loaded translation file overrides any following ones if the same translation is present.
	 *
	 * Locales found in:
	 *      - WP_LANG_DIR/personalized-api-fetcher/personalized-api-fetcher-LOCALE.mo
	 *      - WP_LANG_DIR/plugins/personalized-api-fetcher-LOCALE.mo
	 */
	private static function load_plugin_textdomain() {

		// Add plugin's locale.
		$locale = apply_filters( 'plugin_locale', get_locale(), 'personalized-api-fetcher' );

		load_textdomain( 'personalized-api-fetcher', WP_LANG_DIR . '/personalized-api-fetcher/personalized-api-fetcher-' . $locale . '.mo' );

		load_plugin_textdomain( 'personalized-api-fetcher', false, plugin_basename( dirname( __FILE__ ) ) . '/i18n/languages' );
	}

	public static function add_user_preference_endpoint() {
		// Force update rewrite rules only once.
		$current_rewrite_rule = get_option( 'paf_my_account_custom_tab' );

		if ( ! $current_rewrite_rule || PAF_MY_ACCOUNT_CUSTOM_ENDPOINT !== $current_rewrite_rule ) {
			flush_rewrite_rules( true );
			update_option( 'paf_my_account_custom_tab', PAF_MY_ACCOUNT_CUSTOM_ENDPOINT );
		}

		add_rewrite_endpoint( 'paf-user-preferences', EP_ROOT | EP_PAGES );
	}

	public static function user_preference_query_vars( $vars ) {
		$vars[] = 'paf-user-preferences';

		return $vars;
	}

	public static function add_user_preference_link_my_account( $items ) {
		$items['paf-user-preferences'] = 'User Preferences';

		return $items;
	}

	public static function user_preference_content() {
		?>
		<h2><?php echo esc_html__( 'Settings', 'personalized-api-fetcher' ); ?></h2>

		<form id="paf_preference_field_form" method="post">
			<p>
				<label for="paf_preference_field"><?php _e( 'Enter headers separated by commas. Example: date,content-type,content-length,server,access-control-allow-origin,access-control-allow-credentials', 'woocommerce' ); ?></label><br>
				<input type="text" id="paf_preference_field" name="paf_preference_field" value="<?php echo esc_attr( get_user_meta( get_current_user_id(), 'paf_preference_field', true ) ); ?>">
			</p>
			<p>
				<input type="submit" class="button" name="submit_paf_preference_field" value="<?php _e( 'Submit', 'woocommerce' ); ?>">
			</p>
		</form>
		<div id="paf_preference_field_message"></div>

		<h2><?php echo esc_html__( 'Data', 'personalized-api-fetcher' ); ?></h2>
		<?php
			$api_data = self::api_fetch_data();

			if ( ! empty( $api_data ) ) {
				echo '<ul>';
				foreach ($api_data as $key => $value) {
					echo '<li>' . $key . ' : ' . $value . '</li>';
				}
				echo '</ul>';
			} else {
				echo __( 'No data available.', 'personalized-api-fetcher' );
			}
	}

	public static function save_preference_field_data() {
		if ( isset( $_POST['paf_preference_field'] ) && isset( $_POST['action'] ) && $_POST['action'] == 'save_paf_preference_field' ) {
			$paf_preference_field_value = sanitize_text_field( $_POST['paf_preference_field'] );

			update_user_meta( get_current_user_id(), 'paf_preference_field', $paf_preference_field_value );

			echo 'success';
		}

		wp_die();
	}

	public static function api_fetch_data() {
		$user_preferences = get_user_meta( get_current_user_id(), 'paf_preference_field', true );
		$request_data     = explode( ',', $user_preferences );

		if ( ! empty( $user_preferences ) ) {
			$args = [
				'body' => [
					$request_data,
				],
				'timeout' => 30,
			];

			$response = wp_remote_post( 'https://httpbin.org/post', $args );

			if ( is_wp_error( $response ) ) {
				return [];
			}

			$headers = wp_remote_retrieve_headers( $response );

			$headers_array = [];

			foreach ( $headers as $key => $value ) {
				$headers_array[ $key ] = $value;
			}

			$values = [];

			foreach ( $request_data as $key ) {
				if ( array_key_exists( $key, $headers_array ) ) {
					$values[ $key ] = $headers_array[ $key ];
				} else {
					$values[] = '';
				}
			}

			if ( ! empty( $values ) ) {
				return $values;
			}

			return [];
		}

		return [];
	}
}
