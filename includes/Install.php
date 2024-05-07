<?php
/**
 * Handle plugin's install actions.
 *
 * @class       Install
 * @version     1.0.0
 * @package     Personalized_Api_Fetcher/Classes/
 */

namespace Personalized_Api_Fetcher;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Install class
 */
final class Install {

	/**
	 * Install action.
	 */
	public static function install() {

		// Perform install actions here.

		// Trigger action.
		do_action( 'personalized_api_fetcher_installed' );
	}
}
