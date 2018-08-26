<?php
/**
 * Plugin Name: PMPro Helpers
 * Plugin URI: https://bitbucket.org/pbrocks/
 * Description: Helpful Paid Memberships Pro functions housed in namespaced classes
 * Version: 0.1.2
 * Author: pbrocks
 * Author URI: https://bitbucket.org/pbrocks/
 */

namespace PMPro_Helpers;

defined( 'ABSPATH' ) || die( 'File cannot be accessed directly' );

/**
 * Description
 *
 * @return type Words
 */
require_once( 'autoload.php' );
inc\classes\PMPro_Helper_Functions::init();
inc\classes\PMPro_Menu_Adjustments::init();
inc\classes\PMPro_Primer::init();
inc\classes\PMPro_Setup_Functions::init();
