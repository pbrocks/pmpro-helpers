<?php
/**
 * Plugin Name: PMPro Helpers
 * Plugin URI: https://github.com/pbrocks/pmpro-helpers
 * Description: Helpful Paid Memberships Pro functions housed in namespaced classes as a place for testing and inspiring PMPro development.
 * Version: 0.1.4
 * Author: pbrocks
 * Author URI: https://github.com/pbrocks/pmpro-helpers
 */

namespace PMPro_Helpers;

defined( 'ABSPATH' ) || die( 'File cannot be accessed directly' );

/**
 * Description
 *
 * @return type Words
 */
require_once( 'autoload.php' );
inc\classes\PMPro_Addon_Adjustments::init();
inc\classes\PMPro_Beta_Menu::init();
inc\classes\PMPro_Beta_Temp::init();
inc\classes\PMPro_Helper_Functions::init();
inc\classes\PMPro_Primer::init();
inc\classes\PMPro_Setup_Functions::init();
inc\classes\PMPro_Tabbed_Settings::init();
