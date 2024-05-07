<?php
/**
 * Register admin assets.
 *
 * @class       AdminAssets
 * @version     1.0.0
 * @package     Personalized_Api_Fetcher/Classes/
 */

namespace Personalized_Api_Fetcher\Admin;

use Personalized_Api_Fetcher\Assets as AssetsMain;
use Personalized_Api_Fetcher\Utils;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Admin assets class
 */
final class Assets {

	/**
	 * Hook in methods.
	 */
	public static function hooks() {
		add_filter( 'personalized_api_fetcher_enqueue_styles', array( __CLASS__, 'add_styles' ), 9 );
		add_filter( 'personalized_api_fetcher_enqueue_scripts', array( __CLASS__, 'add_scripts' ), 9 );
		add_action( 'admin_enqueue_scripts', array( AssetsMain::class, 'load_scripts' ) );
		add_action( 'admin_print_scripts', array( AssetsMain::class, 'localize_printed_scripts' ), 5 );
		add_action( 'admin_print_footer_scripts', array( AssetsMain::class, 'localize_printed_scripts' ), 5 );
	}


	/**
	 * Add styles for the admin.
	 *
	 * @param array $styles Admin styles.
	 * @return array<string,array>
	 */
	public static function add_styles( $styles ) {

		$styles['personalized-api-fetcher-admin'] = array(
			'src' => AssetsMain::localize_asset( 'css/admin/personalized-api-fetcher.css' ),
		);

		return $styles;
	}


	/**
	 * Add scripts for the admin.
	 *
	 * @param  array $scripts Admin scripts.
	 * @return array<string,array>
	 */
	public static function add_scripts( $scripts ) {

		$scripts['personalized-api-fetcher-admin'] = array(
			'src'  => AssetsMain::localize_asset( 'js/admin/personalized-api-fetcher.js' ),
			'data' => array(
				'ajax_url' => Utils::ajax_url(),
			),
		);

		return $scripts;
	}
}
