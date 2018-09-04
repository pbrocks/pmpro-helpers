<?php

namespace PMPro_Helpers\inc\classes;

defined( 'ABSPATH' ) || die( 'File cannot be accessed directly' );

/**
 * An example of how to write code to PEAR's standards
 *
 * Docblock comments start with "/**" at the top.  Notice how the "/"
 * lines up with the normal indenting and the asterisks on subsequent rows
 * are in line with the first asterisk.  The last line of comment text
 * should be immediately followed on the next line by the closing
 *
 * @category   CategoryName
 * @package    PackageName
 * @author     pbrocks <author@example.com>
 */
class PMPro_Addon_Adjustments {
	/**
	 * [init description]
	 *
	 * @return [type] [description]
	 */
	public static function init() {
		add_action( 'init', array( __CLASS__, 'pmpro_active_addons_check' ) );
	}

	/**
	 * Description
	 *
	 * @return type
	 */
	public static function pmpro_active_addons_check() {
		if ( function_exists( 'pmproal_load_textdomain' ) ) {
			remove_action( 'wp_enqueue_scripts', 'pmpro_advanced_levels_register_styles' );
			add_action( 'wp_enqueue_scripts', array( __CLASS__, 'pmpro_advanced_levels_new_styles' ) );
		}

		if ( function_exists( 'pmproama_load_plugin_textdomain' ) ) {
			remove_action( 'admin_menu', 'pmproama_pmpro_add_pages' );
			add_action( 'admin_menu', 'pmproama_pmpro_add_pages', 15 );
		}
	}

	public static function pmpro_advanced_levels_new_styles() {
		wp_register_style( 'pmpro-advanced-levels-styles', plugins_url( 'css/pmpro-advanced-levels.css', dirname( __FILE__ ) ), '1.1' );
		wp_enqueue_style( 'pmpro-advanced-levels-styles' );
	}
}
