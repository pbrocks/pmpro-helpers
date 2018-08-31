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
class PMPro_Beta_Menu {
	/**
	 * [init description] register_form
	 * pmpro_add_member_added
	 *
	 * @return [type] [description] pmprorh_register_form
	 */
	public static function init() {
		add_action( 'admin_menu', array( __CLASS__, 'pmpro_beta' ) );
		add_action( 'init', array( __CLASS__, 'beta_admin_init' ) );
		add_action( 'admin_footer', array( __CLASS__, 'beta_diagnostic_message' ) );
		add_action( 'pmpro_add_member_added', array( __CLASS__, 'pmpro_add_forum_role' ), 11 );
		add_filter( 'pmpro_beta_title', array( __CLASS__, 'pmpro_change_menu_name' ), 10, 3 );
	}

	/**
	 * pmpro_add_pages_priority Allows you to rename Dashboard Menu
	 *
	 * @return type
	 */
	public static function pmpro_change_menu_name() {
		$pmpro_menu_title = 'Memberships';
		$pmpro_menu_title = 'PMPro Admin';
		$pmpro_menu_title = 'PMPro Beta';
		return $pmpro_menu_title;
	}

	/**
	 * pmpro_add_pages_priority Allows you to rename Dashboard Menu
	 *
	 * @return type
	 */
	public static function pmpro_add_pages_priority() {
		global $wpdb;

		// array of all caps in the menu
		$pmpro_caps = pmpro_getPMProCaps();

		// the top level menu links to the first page they have access to
		foreach ( $pmpro_caps as $cap ) {
			if ( current_user_can( $cap ) ) {
				$top_menu_cap = $cap;
				break;
			}
		}

		if ( empty( $top_menu_cap ) ) {
			return;
		}
		$pmpro_menu_title = apply_filters( 'pmpro_menu_title', 'PMPro' );

		add_menu_page( __( $pmpro_menu_title, 'paid-memberships-pro' ), __( $pmpro_menu_title, 'paid-memberships-pro' ), 'pmpro_memberships_menu', 'pmpro-membershiplevels', $top_menu_cap, 'dashicons-groups', 20 );
		add_submenu_page( 'pmpro-membershiplevels', __( 'Page Settings', 'paid-memberships-pro' ), __( 'Page Settings', 'paid-memberships-pro' ), 'pmpro_pagesettings', 'pmpro-pagesettings', 'pmpro_pagesettings' );
		add_submenu_page( 'pmpro-membershiplevels', __( 'Payment Settings', 'paid-memberships-pro' ), __( 'Payment Settings', 'paid-memberships-pro' ), 'pmpro_paymentsettings', 'pmpro-paymentsettings', 'pmpro_paymentsettings' );
		add_submenu_page( 'pmpro-membershiplevels', __( 'Email Settings', 'paid-memberships-pro' ), __( 'Email Settings', 'paid-memberships-pro' ), 'pmpro_emailsettings', 'pmpro-emailsettings', 'pmpro_emailsettings' );
		add_submenu_page( 'pmpro-membershiplevels', __( 'Advanced Settings', 'paid-memberships-pro' ), __( 'Advanced Settings', 'paid-memberships-pro' ), 'pmpro_advancedsettings', 'pmpro-advancedsettings', 'pmpro_advancedsettings' );
		add_submenu_page( 'pmpro-membershiplevels', __( 'Add Ons', 'paid-memberships-pro' ), __( 'Add Ons', 'paid-memberships-pro' ), 'pmpro_addons', 'pmpro-addons', 'pmpro_addons' );
		add_submenu_page( 'pmpro-membershiplevels', __( 'Members List', 'paid-memberships-pro' ), __( 'Members List', 'paid-memberships-pro' ), 'pmpro_memberslist', 'pmpro-memberslist', 'pmpro_memberslist' );
		add_submenu_page( 'pmpro-membershiplevels', __( 'Reports', 'paid-memberships-pro' ), __( 'Reports', 'paid-memberships-pro' ), 'pmpro_reports', 'pmpro-reports', 'pmpro_reports' );
		add_submenu_page( 'pmpro-membershiplevels', __( 'Orders', 'paid-memberships-pro' ), __( 'Orders', 'paid-memberships-pro' ), 'pmpro_orders', 'pmpro-orders', 'pmpro_orders' );
		add_submenu_page( 'pmpro-membershiplevels', __( 'Discount Codes', 'paid-memberships-pro' ), __( 'Discount Codes', 'paid-memberships-pro' ), 'pmpro_discountcodes', 'pmpro-discountcodes', 'pmpro_discountcodes' );

		// updates page only if needed
		if ( pmpro_isUpdateRequired() ) {
			add_submenu_page( 'pmpro-membershiplevels', __( 'Updates Required', 'paid-memberships-pro' ), __( 'Updates Required', 'paid-memberships-pro' ), 'pmpro_updates', 'pmpro-updates', 'pmpro_updates' );
		}

		// rename the automatically added Memberships submenu item
		global $submenu;
		if ( ! empty( $submenu['pmpro-membershiplevels'] ) ) {
			if ( current_user_can( 'pmpro_membershiplevels' ) ) {
				$submenu['pmpro-membershiplevels'][0][0] = __( 'Membership Levels', 'paid-memberships-pro' );
				$submenu['pmpro-membershiplevels'][0][3] = __( 'Membership Levels', 'paid-memberships-pro' );
			} elseif ( current_user_can( $top_menu_cap ) ) {
				unset( $submenu['pmpro-membershiplevels'][0] );
			} else {
				unset( $submenu['pmpro-membershiplevels'] );
			}
		}

	}

	public static function pmpro_beta() {
		$labels = self::pmpro_beta_menu_labels();
		$page_title = $labels['page_title'];
		$menu_title = $labels['menu_title'];
		$capability = $labels['capability'];
		$menu_slug = $labels['menu_slug'];
		$function = array( __CLASS__, 'pmpro_beta_page' );
		$icon_url = 'dashicons-groups';
		$position = 20;
		$num_submenus = 3;
		add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position );
		$x = 0;
		while ( $x < $num_submenus ) {
			$x++;
			add_submenu_page( $menu_slug, $page_title, 'PMPro Beta ' . $x, $capability, 'pmpro-beta-menu-' . $x . '.php', array( __CLASS__, 'pmpro_beta_page_' . $x ) );
		}

		// $submenus = 3;
		// $x = 1;
		// while ( $x <= $submenus ) {
		// $submenu_title = 'PMPro Beta ' . $x;
		// $submenu_slug = 'pmpro-beta-menu' . $x . '.php';
		// $submenu_function = array( __CLASS__, 'pmpro_beta_page_' . $x );
		// add_submenu_page( $menu_slug, $page_title, $submenu_title . $x, $capability, $submenu_slug, $submenu_function );
		// }
	}

	public static function pmpro_beta1() {
		global $beta_dash;
		$slug = preg_replace( '/_+/', '-', __FUNCTION__ );
		$label = ucwords( preg_replace( '/_+/', ' ', __FUNCTION__ ) );
		$pmpro_beta_title = apply_filters( 'pmpro_beta_title', 'PMPro Beta' );
		$beta_dash = add_menu_page( __( $label, 'pmpro-beta' ), __( $label, 'pmpro-beta' ), 'manage_options', $slug . '.php', array( __CLASS__, 'pmpro_beta_page' ), 'dashicons-groups', 20 );

		add_submenu_page( $slug . '.php', __( $pmpro_beta_title . ' Sub 1', 'paid-memberships-pro' ), __( $pmpro_beta_title . ' Sub 1', 'paid-memberships-pro' ), 'manage_options', array( __CLASS__, 'pmpro_beta_submenu_1' ) );
		add_submenu_page( $slug . '.php', __( $pmpro_beta_title . ' Sub 2', 'paid-memberships-pro' ), __( $pmpro_beta_title . ' Sub 2', 'paid-memberships-pro' ), 'manage_options', array( __CLASS__, 'pmpro_beta_submenu_2' ) );
		// add_submenu_page( $slug . '.php', __( 'Page Settings', 'paid-memberships-pro' ), __( 'Page Settings', 'paid-memberships-pro' ), 'manage_options', array( __CLASS__, 'pmpro_beta_page' ) );
		// add_action( "load-{$beta_dash}", 'beta_tabbed_settings' );
		// $beta_menu_filter = apply_filters(
		// 'beta_menu_filter',
		// add_submenu_page( $slug . '.php', __( $pmpro_beta_title . ' Sub 3', 'paid-memberships-pro' ), __( $pmpro_beta_title . ' Sub 3', 'paid-memberships-pro' ), 'manage_options', array( __CLASS__, 'pmpro_beta_submenu_2' ) )
		// );
	}
	public static function pmpro_beta_menu_labels() {
		$return['page_title'] = 'PMPro Beta Menus';
		$return['menu_title'] = 'PMPro Beta';
		$return['capability'] = 'manage_options';
		$return['menu_slug'] = 'pmpro-beta-menu.php';
		return $return;
	}


	public static function pmpro_add_forum_role( $user_id ) {
		$user_role = 'subscriber';
		$bbp_role = 'bbp_participant';

		if ( is_wp_error( $user_id ) ) {
			echo $return->get_error_message();
		}

		if ( ! empty( $_POST['role'] ) ) {
			$user_role = $_POST['role'];
		}
		if ( ! empty( $_POST['bbp-forums-role'] ) ) {
			$bbp_role = $_POST['bbp-forums-role'];
		}
		// $capabilities_item[] = $bbp_role;
		$capabilities_array = array(
			$user_role       => true,
			$bbp_role        => true,
		);
		$updated_roles = update_user_meta( $user_id, 'wp_capabilities', $capabilities_array );
	}


	/**
	 * Debug Information
	 *
	 * @since 1.0.0
	 *
	 * @param bool $html Optional. Return as HTML or not
	 *
	 * @return string
	 */
	public static function pmpro_beta_page1() {
		global $pmpro_levels;
		echo '<div class="wrap">';
		echo '<h2>' . ucwords( preg_replace( '/_+/', ' ', __FUNCTION__ ) ) . '</h2>';
		$screen = get_current_screen();
		echo '<h4 style="color:rgba(250,128,114,.7);">Current Screen is <span style="color:rgba(250,128,114,1);">' . $screen->id . '</span></h4>';

		// Let's grab the tabs that we created in the `tabbed-settings.php`
		// beta_admin_tabs( $current = 'homepage' );
		// self::beta_tabbed_settings();
		$this_theme = wp_get_theme();
		echo '<h4>Theme is ' . sprintf(
			__( '%1$s and is version %2$s', 'text-domain' ),
			$this_theme->get( 'Name' ),
			$this_theme->get( 'Version' )
		) . '</h4>';
		echo '<h4>Templates found in ' . get_template_directory() . '</h4>';
		echo '<h4>Stylesheet found in ' . get_stylesheet_directory() . '</h4>';

		echo '<pre>';
		print_r( $pmpro_levels );
		echo '</pre>';
		echo '</div>';
	}

	public static function pmpro_beta_page() {
		global $pmpro_levels;
		echo '<div class="wrap">';
		echo '<h2>' . ucwords( preg_replace( '/_+/', ' ', __FUNCTION__ ) ) . '</h2>';
		$screen = get_current_screen();
		echo '<h4 style="color:rgba(250,128,114,.7);">Current Screen is <span style="color:rgba(250,128,114,1);">' . $screen->id . '</span></h4>';

		echo '</div>';
	}

	public static function pmpro_beta_page_1() {
		global $pmpro_levels;
		echo '<div class="wrap">';
		echo '<h2>' . ucwords( preg_replace( '/_+/', ' ', __FUNCTION__ ) ) . '</h2>';
		$screen = get_current_screen();
		echo '<h4 style="color:rgba(250,128,114,.7);">Current Screen is <span style="color:rgba(250,128,114,1);">' . $screen->id . '</span></h4>';
		echo '<h2>' . __FUNCTION__ . '</h2>';

		echo '</div>';
	}

	public static function pmpro_beta_page_2() {
		global $pmpro_levels;
		echo '<div class="wrap">';
		echo '<h2>' . ucwords( preg_replace( '/_+/', ' ', __FUNCTION__ ) ) . '</h2>';
		$screen = get_current_screen();
		echo '<h4 style="color:rgba(250,128,114,.7);">Current Screen is <span style="color:rgba(250,128,114,1);">' . $screen->id . '</span></h4>';
		echo '<h2>' . __FUNCTION__ . '</h2>';

		echo '</div>';
	}

	public static function pmpro_beta_page_3() {
		global $pmpro_levels;
		echo '<div class="wrap">';
		echo '<h2>' . ucwords( preg_replace( '/_+/', ' ', __FUNCTION__ ) ) . '</h2>';
		$screen = get_current_screen();
		echo '<h4 style="color:rgba(250,128,114,.7);">Current Screen is <span style="color:rgba(250,128,114,1);">' . $screen->id . '</span></h4>';
		echo '<h2>' . __FUNCTION__ . '</h2>';
		echo '</div>';
	}

	public static function beta_admin_init() {
		$beta_settings = get_option( 'beta_tabbed_settings' );
		if ( empty( $beta_settings ) ) {
			$beta_settings = array(
				'beta_intro' => 'Some intro text for the home page',
				'beta_tag_class' => false,
				'beta_code' => false,
			);
			add_option( 'beta_tabbed_settings', $beta_settings, '', 'yes' );
		}
	}


	public static function beta_save_theme_settings() {
		global $pagenow;
		$beta_settings = get_option( 'beta_tabbed_settings' );
		if ( $pagenow == 'admin.php' && $_GET['page'] == 'pmpro-beta.php' ) {
			if ( isset( $_GET['tab'] ) ) {
				$tab = $_GET['tab'];
			} else {
				$tab = 'homepage';
			}

			switch ( $tab ) {
				case 'general':
					$beta_settings['beta_tag_class']    = $_POST['beta_tag_class'];
					break;
				case 'footer':
					$beta_settings['beta_code']  = $_POST['beta_code'];
					break;
				case 'homepage':
					$beta_settings['beta_intro']    = $_POST['beta_intro'];
					break;
			}
		}

		if ( ! current_user_can( 'unfiltered_html' ) ) {
			if ( $beta_settings['beta_code'] ) {
				$beta_settings['beta_code'] = stripslashes( esc_textarea( wp_filter_post_kses( $beta_settings['beta_code'] ) ) );
			}
			if ( $beta_settings['beta_intro'] ) {
				$beta_settings['beta_intro'] = stripslashes( esc_textarea( wp_filter_post_kses( $beta_settings['beta_intro'] ) ) );
			}
		}

		$updated = update_option( 'beta_tabbed_settings', $beta_settings );
	}

	public static function beta_diagnostic_message( $user ) {
		global $current_user;
		echo '<div id="footer-diagnostic"><div></div><div>$_GET <pre>';
		echo '$current_user ' . $current_user->ID . '<br>';
		if ( ! empty( $_REQUEST['user'] ) ) {
			$user_id = intval( $_REQUEST['user'] );
			$user = get_userdata( $user_id );
			if ( empty( $user->ID ) ) {
				$user_id = false;
			}
		} else {
			$user = get_userdata( 0 );
		}

		if ( isset( $user->ID ) ) {
			echo '$user->ID  ' . $user->ID . '<br>';
		} elseif ( isset( $user_id ) ) {
			echo '$user_id ' . $user_id . '<br>';
		} else {
			echo 'wtf';
		}
		print_r( $_GET );
		echo '</pre></div>';
		echo '<div>$_REQUEST <pre>';
		print_r( $_REQUEST );
		echo '</pre></div>';
		echo '<div>$_POST <pre>';
		print_r( $_POST );
		echo '</pre></div><div class="full">';
		echo __FUNCTION__;
		echo '<br>Line ' . __LINE__ . '</div></div>';

		// $message = '<h3 id="footer-diagnostic"><span id="footer_counter"></span>' . $xyz . ' <br> ' . $stg . '</h3>';
		// if ( current_user_can( 'manage_options' ) ) {
		// echo $message;
		// }
	}




	public static function beta_admin_tabs( $current = 'homepage' ) {
		$tabs = array(
			'homepage' => 'Home',
			'general' => 'General',
			'settings' => 'Settings',
			'footer' => 'Primer',
		);
		$links = array();
		echo '<div id="icon-themes" class="icon32"><br></div>';
		echo '<h2 class="nav-tab-wrapper">';
		foreach ( $tabs as $tab => $name ) {
			$class = ( $tab == $current ) ? ' nav-tab-active' : '';
			echo "<a class='nav-tab$class' href='?page=pmpro-beta.php&tab=$tab'>$name</a>";

		}
		echo '</h2>';
	}

	public static function beta_tabbed_settings() {
		global $pagenow;
		$beta_settings = get_option( 'beta_tabbed_settings' );
		if ( 'true' == esc_attr( $_GET['updated'] ) ) {
			echo '<div class="updated" ><p> Settings updated.</p></div>';
		}

		if ( isset( $_GET['tab'] ) ) {
			self::beta_admin_tabs( $_GET['tab'] );
		} else {
			self::beta_admin_tabs( 'homepage' );
		}
		?>
<style type="text/css">
	#footer-head {
		text-align: center;
	}
</style>
<div id="poststuff">
	<div class="tab-content-wrapper">
		<form method="post" action="<?php admin_url( 'admin.php?page=pmpro-beta.php' ); ?>">
			<?php
			// wp_nonce_field( 'beta-settings-page' );
			if ( $pagenow == 'admin.php' && $_GET['page'] == 'pmpro-beta.php' ) {

				if ( isset( $_GET['tab'] ) ) {
					$tab = $_GET['tab'];
				} else {
					$tab = 'homepage';
				}

				echo '<table class="form-table">';
				switch ( $tab ) {
					case 'general':
						?>
						<tr>
							<th><label for="beta_tag_class">Tags with CSS classes:</label></th>
							<td>
								<?php echo self::pmpro_beta_home(); ?>
							</td>
						</tr>
						<?php
						break;
					case 'settings':
						?>
						<tr>
							<th><label for="beta_settings">$beta_settings:</label></th>
							<td>
							<pre>
								<?php
								print_r( $beta_settings );
								?>
							</pre>
								<br/>
								<span class="description">This shows what has been saved to the database so far.</span>
							</td>
						</tr>
						<?php
						break;
					case 'footer':
						?>
						<tr>
							<th><label for="beta_code">Insert tracking code:</label></th>
							<td>
								<textarea id="beta_code" name="beta_code" cols="60" rows="5"><?php echo esc_html( stripslashes( $beta_settings['beta_code'] ) ); ?></textarea><br/>
								<span class="description">Enter your Google Analytics tracking code:</span>
							</td>
						</tr>
						<?php
						break;
					case 'homepage':
						?>
						<tr>
							<th><label for="beta_intro"><?php echo $tab; ?></label></th>
							<td>
								<?php echo self::pmpro_beta_home(); ?>

								<textarea id="beta_intro" name="beta_intro" cols="60" rows="5" ><?php echo esc_html( stripslashes( $beta_settings['beta_intro'] ) ); ?></textarea><br/>
								<span class="description">Enter the introductory text for the home page:</span>
							</td>
						</tr>
						<?php
						break;
				}
				echo '</table>';
			}
			?>
					<p class="submit" style="clear: both;">
						<input type="submit" name="Submit"  class="button-primary" value="Update Settings" />
						<input type="hidden" name="beta-settings-submit" value="Y" />
					</p>
			</form>
		</div>
	</div>
</div>
		<?php

	}

	/**
	 * [pmpro_beta_home description]
	 * PMPro_Helpers\inc\classes\PMPro_Primer\pmpro_primer_page();
	 *
	 * @return [type] [description]
	 */
	public static function pmpro_beta_home() {
		echo __FUNCTION__;
	}
	/**
	 * [pmpro_beta_home description]
	 * PMPro_Helpers\inc\classes\PMPro_Primer\pmpro_primer_page();
	 *
	 * @return [type] [description]
	 */
	public static function pmpro_beta_submenu_1() {
		echo __FUNCTION__;
	}
	/**
	 * [pmpro_beta_home description]
	 * PMPro_Helpers\inc\classes\PMPro_Primer\pmpro_primer_page();
	 *
	 * @return [type] [description]
	 */
	public static function pmpro_beta_submenu_2() {
		echo __FUNCTION__;
	}
	/**
	 * [pmpro_beta_home description]
	 * PMPro_Helpers\inc\classes\PMPro_Primer\pmpro_primer_page();
	 *
	 * @return [type] [description]
	 */
	public static function pmpro_beta_submenu_3() {
		echo __FUNCTION__;
	}
	/**
	 * [pmpro_beta_home description]
	 * PMPro_Helpers\inc\classes\PMPro_Primer\pmpro_primer_page();
	 *
	 * @return [type] [description]
	 */
	public static function pmpro_beta_submenu_4() {
		echo __FUNCTION__;
	}
}
