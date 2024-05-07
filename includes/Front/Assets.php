<?php
/**
 * Register frontend assets.
 *
 * @class       FrontAssets
 * @version     1.0.0
 * @package     Personalized_Api_Fetcher/Classes/
 */

namespace Personalized_Api_Fetcher\Front;

use Personalized_Api_Fetcher\Assets as AssetsMain;
use Personalized_Api_Fetcher\Utils;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Frontend assets class
 */
final class Assets {

	/**
	 * Hook in methods.
	 */
	public static function hooks() {
		add_filter( 'personalized_api_fetcher_enqueue_styles', array( __CLASS__, 'add_styles' ), 9 );
		add_filter( 'personalized_api_fetcher_enqueue_scripts', array( __CLASS__, 'add_scripts' ), 9 );
		add_action( 'wp_enqueue_scripts', array( AssetsMain::class, 'load_scripts' ) );
		add_action( 'wp_print_scripts', array( AssetsMain::class, 'localize_printed_scripts' ), 5 );
		add_action( 'wp_print_footer_scripts', array( AssetsMain::class, 'localize_printed_scripts' ), 5 );
	}


	/**
	 * Add styles for the admin.
	 *
	 * @param array $styles Admin styles.
	 * @return array<string,array>
	 */
	public static function add_styles( $styles ) {

		$styles['personalized-api-fetcher-general'] = array(
			'src' => AssetsMain::localize_asset( 'css/frontend/personalized-api-fetcher.css' ),
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
		$scripts['personalized-api-fetcher-general'] = [
			'src'  => AssetsMain::localize_asset( 'js/frontend/personalized-api-fetcher.js' ),
			'data' => [
				'ajax_url' => Utils::ajax_url(),
			],
		];

		return $scripts;
	}
}
