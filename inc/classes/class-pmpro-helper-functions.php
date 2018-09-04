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
class PMPro_Helper_Functions {
	/**
	 * [init description]
	 *
	 * @return [type] [description]
	 */
	public static function init() {
		add_action( 'admin_menu', array( __CLASS__, 'pmpro_quick_dashboard_menu' ) );
	}

	/**
	 * [pmpro_quick_dashboard_menu description]
	 *
	 * @return [type] [description]
	 */
	public static function pmpro_quick_dashboard_menu() {
		add_dashboard_page( __( 'PMPro Helpers', 'pmpro-helpers' ), __( 'PMPro Helpers', 'pmpro-helpers' ), 'manage_options', 'pmpro-helper-dash.php', array( __CLASS__, 'pmpro_helper_dashboard_page' ) );
	}

	/**
	 * Description
	 *
	 * @return type
	 */
	public static function pmpro_helper_dashboard_page() {
		global $pmpro_levels;
		echo '<div class="wrap">';
		echo '<h2>' . __FUNCTION__ . '</h2>';

		echo '<h4>pmpro_primer_levels_dropdown()</h4>';
		echo '<pre>';
		print_r( self::pmpro_primer_levels_dropdown() );
		echo '</pre>';

		echo '<h4>get_pmpro_levels_object()</h4>';
		echo '<pre>';
		print_r( self::get_pmpro_levels_object() );
		echo '</pre>';

		echo '<h4>get_pmpro_levels_array()</h4>';
		echo '<pre>';
		print_r( self::get_pmpro_levels_array() );
		echo '</pre>';

		echo '<h4>get_pmpro_levels_dropdown()</h4>';
		echo self::get_pmpro_levels_dropdown();

		echo '<h4>get_pmpro_levels_shortlist()</h4>';
		echo '<pre>';
		print_r( self::get_pmpro_levels_shortlist() );
		echo '</pre>';

		echo '<h4>get_pmpro_member_level()</h4>';
		$user_id = 1;
		$pmpro_member_level = self::get_pmpro_member_level( $user_id );
		echo '<pre>$user_id ' . $user_id . ' $pmpro_member_level ';
		print_r( $pmpro_member_level );
		echo '</pre>';

		echo '<h4>get_pmpro_member_array()</h4>';
		$user_id = 1;
		$pmpro_member_array = self::get_pmpro_member_array( $user_id );
		echo '<pre>$user_id ' . $user_id . ' $pmpro_member_array ';
		print_r( $pmpro_member_array );
		echo '</pre>';

		echo '<h4>get_pmpro_member_object()</h4>';
		$user_id = 1;
		$pmpro_member_object = self::get_pmpro_member_object( $user_id );
		echo '<pre>$user_id ' . $user_id . ' $pmpro_member_object ';
		print_r( $pmpro_member_object );
		echo '</pre>';

		echo '<h4>get_pmpro_user_object()</h4>';
		$user_id = 1;
		$pmpro_user_object = self::get_pmpro_user_object( $user_id );
		echo '<pre>$user_id ' . $user_id . ' $pmpro_user_object ';
		print_r( $pmpro_user_object );
		echo '</pre>';

		echo '<h4>get_pmpro_member_caps_array()</h4>';
		$user_id = 1;
		$pmpro_member_caps_array = self::get_pmpro_member_caps_array( $user_id );
		echo '<pre>$user_id ' . $user_id . ' $pmpro_member_caps_array ';
		print_r( $pmpro_member_caps_array );
		echo '</pre>';
		echo '</div>';
	}


	/**
	 * Description
	 *
	 * @return type
	 */
	public static function get_pmpro_levels_object() {
		global $wpdb;
		$existing_levels = $wpdb->get_results(
			"
			SELECT * FROM $wpdb->pmpro_membership_levels
			"
		);
		return $existing_levels;
	}


	/**
	 * Description
	 *
	 * @return type
	 */
	public static function get_pmpro_levels_array() {
		$existing_levels = self::get_pmpro_levels_object();
		foreach ( $existing_levels as $level_key => $level_value ) {
			foreach ( $level_value as $key => $value ) {
				$pmpro_level_list[ $level_key ][ $key ] = $value;
			}
		}
		return $pmpro_level_list;
	}

	/**
	 * Description
	 *
	 * @return type
	 */
	public static function get_pmpro_levels_shortlist() {
		$existing_levels = self::get_pmpro_levels_object();
		$pmpro_level_list = '<ul>';
		foreach ( $existing_levels as $key => $value ) {
			$pmpro_level_list .= '<li>Level ' . $value->id . ' => ' . $value->name . '</li>';
		}
		$pmpro_level_list .= '<ul>';
		return $pmpro_level_list;
	}

	/**
	 * Description
	 *
	 * @return type
	 */
	public static function get_pmpro_levels_dropdown() {
		global $pmpro_levels;
		// if ( 1 < count($pmpro_levels)) {
		// $pmpro_level_dropdown = '<select>';
		// foreach ( $pmpro_levels as $key => $value ) {
		// $pmpro_level_dropdown .= '<option value="' . $value->id . '">' . $value->id . ' => ' . $value->name . '</option>';
		// }
		// $pmpro_level_dropdown .= '<select>';
		// return $pmpro_level_dropdown;
		// }
		return $pmpro_levels[0];
	}

	/**
	 * [get_pmpro_member_level description]
	 *
	 * @param  [type] $user_id [description]
	 * @return [type]           [description]
	 */
	public static function get_pmpro_member_level( $user_id ) {
		$user_object = new \WP_User( $user_id );
		$member_data = get_userdata( $user_object->ID );
		$member_object = pmpro_getMembershipLevelForUser( $member_data->ID );
		// $member_level = $member_object->id;
		return $member_object;
	}

	/**
	 * [get_pmpro_member_array description]
	 *
	 * @param  [type] $user_id [description]
	 * @return [type]           [description]
	 */
	public static function get_pmpro_member_array( $user_id ) {
		$user_object = new \WP_User( $user_id );
		;
		$member_data = get_userdata( $user_object->ID );
		$member_object = pmpro_getMembershipLevelForUser( $member_data->ID );
		$member_level['level_id'] = $member_object->id;
		$member_level['level_name'] = $member_object->name;
		$member_level['level_description'] = $member_object->description;
		$member_level['subscription_id'] = $member_object->subscription_id;
		return $member_level;
	}

	/**
	 * [get_pmpro_member_level description]
	 *
	 * @param  [type] $user_id [description]
	 * @return [type]           [description]
	 */
	public static function get_pmpro_member_object( $user_id ) {
		$user_object = new \WP_User( $user_id );
		$member_data = get_userdata( $user_object->ID );
		$member_object = pmpro_getMembershipLevelForUser( $member_data->ID );
		return $member_object;
	}

	/**
	 * [get_pmpro_member_array description]
	 *
	 * @param  [type] $user_id [description]
	 * @return [type]           [description]
	 */
	public static function get_pmpro_member_caps_array( $user_id ) {
		$user_object = new \WP_User( $user_id );
		$pmpro_member_caps_array = $user_object->allcaps;
		// if ( begins with pmpro_ ) ) {
		// add_to_array();
		// }
		return $pmpro_member_caps_array;
	}

	/**
	 * Description
	 *
	 * @return type
	 */
	public static function get_pmpro_current_user_level() {
		global $current_user;
		if ( ! empty( $current_user->membership_level ) ) {
			$level_id = $current_user->membership_level->id;
		}
		if ( empty( $level_id ) ) {
			$level_id = 0;
		}
		return $level_id;
	}

	/**
	 * [get_pmpro_user_object description]
	 *
	 * @param  [type] $user_id [description]
	 * @return [type]           [description]
	 */
	public static function get_pmpro_user_object( $user_id ) {
		$user_object = new \WP_User( $user_id );
		return $user_object;
	}


	public static function pmpro_primer_levels_dropdown() {
		?>
		<select name="level" id="pmpro-sortable-members-levels">
			<option value="active" <?php selected( '', $level ); ?>><?php _e( 'Active Members', 'pmpro' ); ?></option>
			<option value="all" <?php selected( $level, 'all' ); ?>><?php _e( 'All Members', 'pmpro' ); ?></option>
			<?php

			$list = pmpro_getAllLevels( true, true );

			foreach ( $list as $item ) {
				?>
				<option value="<?php esc_attr_e( $item->id ); ?>" <?php selected( $level, $item->id ); ?>><?php esc_attr_e( $item->name ); ?></option>
				<?php
			}
			?>
			<option value="cancelled" <?php selected( $level, 'cancelled' ); ?>><?php _e( 'Cancelled Members', 'pmpro' ); ?></option>
			<option value="expired" <?php selected( $level, 'expired' ); ?>><?php _e( 'Expired Members', 'pmpro' ); ?></option>
			<option value="oldmembers" <?php selected( $level, 'oldmembers' ); ?>><?php _e( 'Old Members', 'pmpro' ); ?></option>
		</select>
		<?php
	}

	public static function get_level_expiration() {
		$expiration = 'tomorrow';
		return $expiration;
	}

	public static function get_member_expiration() {
		$expiration = 'tomorrow';
		return $expiration;
	}
}
