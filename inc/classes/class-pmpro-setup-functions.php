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
class PMPro_Setup_Functions {
	/**
	 * [init description]
	 *
	 * @return [type] [description]
	 */
	public static function init() {
		add_filter( 'login_redirect', array( __CLASS__, 'pmpro_helpers_login_redirect' ), 10, 3 );
		remove_action( 'admin_menu', 'pmpro_add_pages' );
		add_action( 'admin_menu', array( __CLASS__, 'pmpro_add_pages_priority' ) );
		// add_action( 'admin_menu', array( __CLASS__, 'pmpro_add_new_menu_priority' ), 15 );
		add_filter( 'pmpro_menu_title', array( __CLASS__, 'pmpro_change_menu_name' ) );
		// add_filter( 'beta_menu_filter', array( __CLASS__, 'pmpro_add_new_menu_priority' ) );
	}

	/**
	 * pmpro_helpers_login_redirect Function to redirect member on login to their membership level's homepage
	 *
	 * @param  [type] $redirect_to [description]
	 * @param  [type] $request     [description]
	 * @param  [type] $user        [description]
	 * @return [type]              [description]
	 */
	public static function pmpro_helpers_login_redirect( $redirect_to, $request, $user ) {
		global $pmpro_pages;
		if ( ! empty( $user ) && ! empty( $user->ID ) && function_exists( 'pmpro_getMembershipLevelForUser' ) ) {
			$level = pmpro_getMembershipLevelForUser( $user->ID );
			if ( empty( $level ) ) {
				$redirect_to = get_permalink( $pmpro_pages['account'] );
			} else {
				$redirect_to = get_permalink( $pmpro_pages['invoice'] );
			}
			// TODO check for Member Homepages
			// if ( ! empty( $member_homepage_id ) ) {
			// $redirect_to = get_permalink( $member_homepage_id );
			// }
		}
		return $redirect_to;
	}

	/**
	 * pmpro_add_pages_priority Allows you to rename Dashboard Menu
	 *
	 * @return type
	 */
	public static function pmpro_change_menu_name() {
		$pmpro_menu_title = 'Memberships';
		$pmpro_menu_title = 'PMPro Admin';
		return $pmpro_menu_title;
	}

	/**
	 * pmpro_add_pages_priority Allows you to rename Dashboard Menu
	 *
	 * @return type
	 */
	public static function pmpro_add_new_menu_priority() {
		$menu = add_submenu_page( $slug . '.php', __( $pmpro_beta_title . ' Sub 43', 'paid-memberships-pro' ), __( $pmpro_beta_title . ' Sub 43', 'paid-memberships-pro' ), 'manage_options', array( __CLASS__, 'pmpro_beta_submenu_3' ) );
		return $menu;
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

}
