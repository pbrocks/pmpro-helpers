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

class PMPro_Customizer_Functions {

	public static function init() {
		// add_action( 'admin_enqueue_scripts', array( __CLASS__, 'some_theme_customizer_styles' ) );
		// add_action( 'customize_controls_init', array( __CLASS__, 'set_customizer_preview_url' ) );
		// add_action( 'wp_enqueue_scripts', array( __CLASS__, 'some_theme_customizer_styles' ) );
		add_action( 'customize_register', array( __CLASS__, 'engage_customizer' ) );
		add_filter( 'after_setup_theme', array( __CLASS__, 'create_customizer_dev_page' ) );
		// add_filter( 'the_content', array( __CLASS__, 'sample_page_content' ) );
	}


	public static function set_customizer_preview_url() {
		global $wp_customize;
		if ( ! isset( $_GET['url'] ) ) {
			$wp_customize->set_preview_url( get_permalink( get_page_by_title( 'Customizer Dev Page' ) ) );
		}
	}

	/**
	 * [engage_customizer description]
	 *
	 * @param [type] $customizer_additions [description]
	 * @return [type]             [description]
	 */
	public static function engage_customizer( $customizer_additions ) {
		self::a_login_panel( $customizer_additions );
	}

	/**
	 * [engage_customizer description]
	 *
	 * @param [type] $customizer_additions [description]
	 * @return [type]             [description]
	 */
	private static function a_login_panel( $customizer_additions ) {

		$customizer_additions->add_panel(
			'a_login_panel', array(
				'title'       => 'Login Customizer',
				'description' => 'This is a description of this ' . __FUNCTION__ . ' panel',
				'priority'    => 10,
			)
		);

		self::a_login_section( $customizer_additions );
		// self::a_login_admin_section( $customizer_additions );
		// self::pbrx_core_footer( $customizer_additions );
		// self::a_login_advanced( $customizer_additions );
		self::some_theme_customizer_options( $customizer_additions );
	}

	/**
	 * The a_login_section function adds a new section
	 * to the Customizer to display the settings and
	 * controls that we build.
	 *
	 * @param  [type] $customizer_additions [description]
	 * @return [type]             [description]
	 */
	private static function a_login_section( $customizer_additions ) {
		$customizer_additions->add_section(
			'a_login_section', array(
				'title'          => 'PMPro Controls',
				'priority'       => 16,
				'panel'          => 'a_login_panel',
				'description' => 'This is a description of this text setting in the PMPro Customizer Controls section of the PMPro panel in <h4>' . __FILE__ . '</h4>',
			)
		);

		$customizer_additions->add_setting(
			'login[the_header]', array(
				'default' => 'header-text default text',
				'type' => 'option',
				'transport' => 'refresh', // refresh (default), postMessage
			// 'capability' => 'edit_theme_options',
			// 'sanitize_callback' => 'sanitize_key'
			)
		);

		$customizer_additions->add_control(
			'login[the_header]', array(
				'section'   => 'a_login_section',
				'type'   => 'text', // text (default), checkbox, radio, select, dropdown-pages
				'label'       => 'Default Header Text',
				'settings'    => 'login[the_header]',
				'description' => 'Description of this text input setting in ' . __FUNCTION__ . ' for Default Header Text',
			)
		);

		$customizer_additions->add_setting(
			'login[the_footer]', array(
				'default' => 'footer-text default text',
				'type' => 'option',
				'transport' => 'refresh', // refresh (default), postMessage
			// 'capability' => 'edit_theme_options',
			// 'sanitize_callback' => 'sanitize_key'
			)
		);

		$customizer_additions->add_control(
			'login[the_footer]', array(
				'section'   => 'a_login_section',
				'type'   => 'text', // text (default), checkbox, radio, select, dropdown-pages
				'label'       => 'Default Footer Text',
				'settings'    => 'login[the_footer]',
				'description' => 'Description of this text input setting in ' . __FUNCTION__ . ' for Default Footer Text',
			)
		);

		/**
		 * Adding a Checkbox Toggle
		 */
		// if ( ! class_exists( 'Customizer_Toggle_Control' ) ) {
		// require_once dirname( __FILE__ ) . '/controls/checkbox/toggle-control.php';
		// }
		/**
		 * Radio control
		 */
		$customizer_additions->add_setting(
			'menu_radio', array(
				'default'        => '2',
			)
		);

		$customizer_additions->add_control(
			'menu_radio', array(
				// 'section'     => 'theme_options',
				'section'     => 'a_login_section',
				'type'        => 'radio',
				'label'       => 'Menu Alignment Radio Buttons',
				'description' => 'Description of this radio setting in ' . __FUNCTION__,
				'choices'     => array(
					'1' => 'left',
					'2' => 'center',
					'3' => 'right',
				),
				'priority'    => 11,
			)
		);

	}
	private static function some_theme_customizer_options( $customizer_additions ) {
		$customizer_additions->add_section(
			'a_login_colors_section', array(
				'title'          => 'PMPro Colors',
				'priority'       => 16,
				'panel'          => 'a_login_panel',
				'description' => 'PMPro Colors section of the PMPro panel in <h4>' . __FILE__ . '</h4>',
			)
		);

		$customizer_additions->add_setting(
			'a_login_colors[header]', array(
				'default' => 'header-text default text',
				'transport' => 'refresh', // refresh (default), postMessage
			// 'capability' => 'edit_theme_options',
			// 'sanitize_callback' => 'sanitize_key'
			)
		);

		$customizer_additions->add_control(
			'a_login_colors[header]', array(
				'section'   => 'a_login_colors_section',
				'type'   => 'text', // text (default), checkbox, radio, select, dropdown-pages
				'label'       => 'Default Header Text',
				'settings'    => 'a_login_colors[header]',
				'description' => 'Description of this text input setting in ' . __FUNCTION__ . ' for Default Text',
			)
		);

		// // Estimate secondary color
		// $customize_additions->add_setting(
		// 'a_login_colors[x_color]', array(
		// 'default'           => '#438cb7',
		// 'sanitize_callback' => 'sanitize_hex_color',
		// 'transport' => 'postMessage',
		// )
		// );
		$customizer_additions->add_setting(
			'a_login_colors[x_color]', array(
				'default' => 'footer-text default text',
				'transport' => 'refresh', // refresh (default), postMessage
			// 'capability' => 'edit_theme_options',
			// 'sanitize_callback' => 'sanitize_key'
			)
		);

		$customizer_additions->add_control(
			'a_login_colors[x_color]', array(
				'section'   => 'a_login_colors_section',
				'type'   => 'text', // text (default), checkbox, radio, select, dropdown-pages
				'label'       => 'Default Footer Text',
				'settings'    => 'a_login_colors[x_color]',
				'description' => 'Description of this text input setting in ' . __FUNCTION__ . ' for Default Text',
			)
		);

		// $customize_additions->add_control(
		// new \WP_Customize_Color_Control(
		// $customize_additions, 'a_login_colors[x_color]', array(
		// 'label'    => __( 'Estimate Header Background', 'sprout-invoices' ),
		// 'section'  => 'a_login_colors_section',
		// 'settings' => 'a_login_colors[x_color]',
		// )
		// )
		// );
		$customizer_additions->add_setting(
			'a_login_colors[footer]', array(
				'default' => 'footer-text default text',
				'transport' => 'refresh', // refresh (default), postMessage
			// 'capability' => 'edit_theme_options',
			// 'sanitize_callback' => 'sanitize_key'
			)
		);

		$customizer_additions->add_control(
			'a_login_colors[footer]', array(
				'section'   => 'a_login_colors_section',
				'type'   => 'text', // text (default), checkbox, radio, select, dropdown-pages
				'label'       => 'Default Footer Text',
				'settings'    => 'a_login_colors[footer]',
				'description' => 'Description of this text input setting in ' . __FUNCTION__ . ' for Default Text',
			)
		);
		/**
 *      $wp_customize->add_section(
			'memberlite_theme_options',
			array(
				'title' => __( 'Memberlite Options', 'memberlite' ),
				'priority' => 35,
				'capability' => 'edit_theme_options',
				'description' => __('Allows you to customize settings for Memberlite.', 'memberlite'),
			)
		);
		$wp_customize->add_setting(
			'memberlite_webfonts',
			array(
				'default' => $memberlite_defaults['memberlite_webfonts'],
				'santize_callback' => 'sanitize_text_field',
				'sanitize_js_callback' => array('memberlite_Customize', 'memberlite_sanitize_js_callback'),
			)
		);
		$wp_customize->add_control(
			'memberlite_webfonts',
			array(
				'label' => 'Google Webfonts',
				'section' => 'memberlite_theme_options',
				'type'       => 'select',
				'choices'    => array(
					'Lato_Lato'  => 'Lato',
					'PT-Sans_PT-Serif'  => 'PT Sans and PT Serif',
					'Fjalla-One_Noto-Sans'  => 'Fjalla One and Noto Sans',
					'Pathway-Gothic-One_Source-Sans-Pro' => 'Pathway Gothic One and Source Sans Pro',
					'Oswald_Lato' => 'Oswald and Lato',
					'Ubuntu_Open-Sans' => 'Ubuntu and Open Sans',
					'Lato_Source-Sans-Pro' => 'Lato and Source Sans Pro',
					'Roboto-Slab_Roboto'  => 'Roboto Slab and Roboto',
					'Lato_Merriweather'  => 'Lato and Merriweather',
					'Playfair-Display_Open-Sans'  => 'Playfair Display and Open Sans',
					'Oswald_Quattrocento'  => 'Oswald and Quattrocento',
					'Abril-Fatface_Open-Sans'  => 'Abril Fatface and Open Sans',
					'Open-Sans_Gentium-Book-Basic' => 'Open Sans and Gentium Book Basic',
					'Oswald_PT-Mono' => 'Oswald and PT Mono'
				),
				'priority' => 10
			)
		);
		$wp_customize->add_setting(
			'meta_login',
			array(
				'default' => false,
				'santize_callback' => 'memberlite_sanitize_checkbox',
				'santize_js_callback' => array('memberlite_Customize', 'memberlite_sanitize_js_callback'),
			)
		);
		$wp_customize->add_control(
			'meta_login',
			array(
				'type' => 'checkbox',
				'label' => 'Show Login/Member Info in Header',
				'section' => 'memberlite_theme_options',
				'priority' => '15'
			)
		);
		$wp_customize->add_setting(
			'nav_menu_search',
			array(
				'default' => false,
				'santize_callback' => 'memberlite_sanitize_checkbox',
				'santize_js_callback' => array('memberlite_Customize', 'memberlite_sanitize_js_callback'),
			)
		);
		$wp_customize->add_control(
			'nav_menu_search',
			array(
				'type' => 'checkbox',
				'label' => 'Show Search Form After Main Nav',
				'section' => 'memberlite_theme_options',
				'priority' => '20'
			)
		);
 */

		// // Invoice main color
		// $customize_additions->add_setting(
		// 'ca_inv_primary_color', array(
		// 'default'           => '#4086b0',
		// 'sanitize_callback' => 'sanitize_hex_color',
		// 'transport' => 'postMessage',
		// )
		// );
		// $customize_additions->add_control(
		// new \WP_Customize_Color_Control(
		// $customize_additions, 'ca_inv_primary_color', array(
		// 'label'    => __( 'Invoice Primary Color', 'sprout-invoices' ),
		// 'section'  => 'a_login_colors_section',
		// 'settings' => 'ca_inv_primary_color',
		// )
		// )
		// );
		// // Invoice secondary color
		// $customize_additions->add_setting(
		// 'ca_inv_secondary_color', array(
		// 'default'           => '#438cb7',
		// 'sanitize_callback' => 'sanitize_hex_color',
		// 'transport' => 'postMessage',
		// )
		// );
		// $customize_additions->add_control(
		// new \WP_Customize_Color_Control(
		// $customize_additions, 'ca_inv_secondary_color', array(
		// 'label'    => __( 'Invoice Header Background', 'sprout-invoices' ),
		// 'section'  => 'a_login_colors_section',
		// 'settings' => 'ca_inv_secondary_color',
		// )
		// )
		// );
		// // Estimate main color
		// $customize_additions->add_setting(
		// 'ca_est_primary_color', array(
		// 'default'           => '#4086b0',
		// 'sanitize_callback' => 'sanitize_hex_color',
		// 'transport' => 'postMessage',
		// )
		// );
		// $customize_additions->add_control(
		// new \WP_Customize_Color_Control(
		// $customize_additions, 'ca_est_primary_color', array(
		// 'label'    => __( 'Estimate Primary Color', 'sprout-invoices' ),
		// 'section'  => 'a_login_colors_section',
		// 'settings' => 'ca_est_primary_color',
		// )
		// )
		// );
	}

	public static function return_something() {
		return '<h3 style="text-align:center;color:salmon;">Something</h3>';
	}
	public static function sample_page_content( $content ) {
		$page = get_page_by_title( 'Customizer Dev Page' );

		if ( is_page( $page->ID ) ) {
			$content = self::return_something();
		}
		return $content;
	}

	public static function create_customizer_dev_page() {
		$customizer_dev_page = 'Customizer Dev Page';
		$customizer_dev_page_content = self::return_something();
		$author_id = get_current_user();
		$check_page = get_page_by_title( $customizer_dev_page );
		if ( null == $check_page ) {
			$my_post = array(
				'post_title'    => wp_strip_all_tags( $customizer_dev_page ),
				// $slug = 'wordpress-post-created-with-code';
				'post_content'  => $customizer_dev_page_content,
				'post_status'   => 'publish',
				'post_type'     => 'page',
				'post_author'   => $author_id,
				// 'post_category' => array( 8,39 ),
			);

			// Insert the post into the database
			wp_insert_post( $my_post );
		}
		/*
		// Initialize the page ID to -1. This indicates no action has been taken.
		$post_id = -1;

		// Setup the author, slug, and title for the post
		$author_id = 1;
		$slug = 'example-post';
		$title = 'My Example Post';
		*/
		// Create the page
		// } else {
		// // The page exists
		// } // end if
	}
}
