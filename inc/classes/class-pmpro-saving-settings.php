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
class PMPro_Saving_Settings {
	/**
	 * The prefix used when settings are stored in the WordPress database
	 *
	 * @var string
	 */
	public static $prefix = '';

	/**
	 * The title of the WordPress Dashboard settings menu item.
	 *
	 * @var string
	 */
	public static $menu_title = '';

	/**
	 * The page title of the settings screen in the WordPress Dashboard.
	 *
	 * @var string
	 */
	public static $page_title = '';

	/**
	 * The CSS `width` value to assign to text inputs.
	 *
	 * @var string
	 */
	public static $input_text_width = '80%';

	/**
	 * [init description]
	 *
	 * @return [type] [description]
	 */
	public static function init() {
		add_action( 'admin_menu', array( __CLASS__, 'setup_menu' ) );
		// add_filter( 'pmpro_menu_title', array( __CLASS__, 'pmpro_change_menu_name' ) );
	}

	/**
	 * pmpro_helpers_login_redirect Function to redirect member on login to their membership level's homepage
	 *
	 * @param  [type] $redirect_to [description]
	 * @param  [type] $request     [description]
	 * @param  [type] $user        [description]
	 * @return [type]              [description]
	 */

	/**
	 * Quick-and-dirty class for managing WordPress options for a plugin.
	 * Include this class in your plugin. Create an instance and use as follows:
	 *
	 * Required Usage:
	 * Plugin Name: Sidetrack Settings class
	 *   // Create new instance
	 *   $settings = new Saving_Our_WP_Settings;
	 *
	 *   // Configure the prefix for storing and referencing your plugin's settings
	 *   $settings->prefix = "plugin_prefix_";
	 *
	 *   // Menu title for settings screen.
	 *   $settings->menu_title = "Name of Plugin";
	 *
	 *   // Add one or more settings fields.
	 *   // Here's a full example. Only required field is `id`
	 *   $settings->add_field( array(
	 *
	 *     # Required. The field identifier.
	 *     "id" => "field_name",
	 *
	 *     # Optional.
	 *     # The title of the field. When empty, defaults to a value derived from
	 *     # the `id`. Used for labeling field inputs.
	 *     "title" => "Field Title",
	 *
	 *     # Optional.
	 *     # Long text description describing the field. Can include HTML tags.
	 *     "description" => "What this field is used for.",
	 *
	 *     # Optional.
	 *     # The type of input to present to the user.
	 *     # Defaults to "text", e.g., `<input type="text">`
	 *     # NOTE: currently "text" is the only supported option.
	 *     "input" => "text",
	 *
	 *     # Optoinal.
	 *     # The default value for the field
	 *     "default" => "",
	 *
	 *   ));
	 *
	 *   //
	 *   // Once configured call `hook()` to integrate into WordPress.
	 *   //
	 *   $settings->hook();
	 *
	 *
	 * Optional Configuration:
	 *
	 *   // Page title for settings screen. Defaults to the menu title.
	 *   $settings->page_title = "Settings for Name of Plugin";
	 *
	 *   // Width of input text fields. Defaults to "80%".
	 *   $settings->input_text_width = "100%";
	 */


	/**
	 * Add a settings field.
	 *
	 * @param array $args
	 */
	public static function add_field( array $args ) {
		$field = self::create_field( $args );
		self::$_fields[ $field['id'] ] = $field;
	}

	/**
	 * Internal collection of settings fields.
	 *
	 * @var array
	 */
	protected $_fields = array();

	/**
	 * Create a settings field array, ensuring defaults are populated.
	 *
	 * @param array $args
	 * @return array
	 */
	public static function create_field( array $args ) {
		$defaults = array(
			'id' => '',
			'title' => '',
			'description' => '',
			'input' => 'text',  // currently only "text"
			'default' => '',
		);
		$field = array_merge( $defaults, $args );
		if ( empty( $field['title'] ) ) {
			$field['title'] = ucwords( implode( ' ', array_map( 'trim', preg_split( '/[_-]+/', $field['id'] ) ) ) );
		}
		return $field;
	}

	/**
	 * Check if a field exists.
	 *
	 * @param string $k The id of the field to check.
	 * @return boolean|array FALSE when field was not found. Otherwise, returns a field array.
	 */
	public static function field_exists( $k ) {
		if ( array_key_exists( $k, self::$_fields ) ) {
			return self::$_fields[ $k ];
		} else {
			return false;
		}
	}

	/**
	 * Format a field id as a unique option key, for storing the field value in the WordPress database.
	 *
	 * @param string $k The field id.
	 * @return string
	 */
	public static function option_key( $k ) {
		if ( empty( self::$prefix ) ) {
			return $k;
		} else {
			return self::$prefix . $k;
		}
	}

	/**
	 * Check if the named settings field exists.
	 *
	 * @param string $k The field id.
	 * @return boolean
	 */
	function __isset( $k ) {
		return ! ! self::field_exists( $k );
	}

	/**
	 * Remove a settings field.
	 *
	 * @param string $k The field id.
	 */
	function __unset( $k ) {
		unset( self::$_fields[ $k ] );
	}

	/**
	 * Get accessor for retrieving settings field values.
	 *
	 * @param string $k The field id
	 * @return mixed NULL when the field was not found. Otherwise, the configured field value. Or the field's default value when a value has not yet been set.
	 */
	function __get( $k ) {
		if ( $field = self::field_exists( $k ) ) {
			$ok = self::option_key( $k );
			$v = get_option( $ok, $field['default'] );
			return $v;
		}
		return null;
	}

	/**
	 * Set accessor for configuring a settings field value
	 *
	 * @param string $k The field id
	 * @param string $v The field value, as fed to `update_option`. Can contain any serializable value.
	 */
	function __set( $k, $v ) {
		if ( self::field_exists( $k ) ) {
			$ok = self::option_key( $k );
			update_option( $ok, $v );
		}
	}

	/**
	 * Hook into WordPress.
	 * Sets up the settings form screens in WordPress Dashboard.
	 */
	public static function hook() {
		if ( is_admin() ) {
			add_action( 'admin_init', array( __CLASS__, 'settings_form_save' ) );
			add_action( 'admin_menu', array( __CLASS__, 'setup_menu' ) );
		}
	}

	/**
	 * Detect and handle form save requests.
	 */
	public static function settings_form_save() {
		$save_action = self::$prefix . 'settings_form_save';
		$do_save = isset( $_POST['action'] ) && $_POST['action'] === $save_action;
		if ( $do_save ) {
			if ( check_admin_referer( $save_action ) ) {
				foreach ( $_POST as $fid => $v ) {
					self::$fid = $v;
				}
				$redirect_url = $_POST['_wp_http_referer'];
				$redirect_strpos = strpos( $redirect_url, '&updated=1', true );
				if ( false === $redirect_strpos ) {
					$redirect_url .= '&updated=1';
				}
				wp_safe_redirect( $redirect_url );
			} else {
				wp_die( 'Unable to save settings: nonce verification failed.' );
			}
		}
	}

	/**
	 * Add settings screen to WordPress dashboard.
	 */
	public static function setup_menu() {
		add_dashboard_page( ( self::$page_title ? self::$page_title : self::$menu_title ), self::$menu_title, 'manage_options', '{self::$prefix}menu', array( __CLASS__, 'settings_screen' ) );
	}

	/**
	 * Output the settings screen.
	 */
	public static function settings_screen() {
		$nonce_name = '{self::$prefix}settings_form_save';
		?>
<div class="wrap">
  <h2><?php echo ( self::$page_title ? self::$page_title : self::$menu_title ); ?></h2>
	<form method="post" action="" class="<?php echo self::$prefix; ?>_settings_form">
		<input type="hidden" name="action" value="<?php echo $nonce_name; ?>">
		<?php wp_nonce_field( $nonce_name ); ?>
		<table class="form-table">
		<?php
		foreach ( self::$_fields as $field_id => $field ) {
			$dom_id = self::$prefix . $field_id;
			?>
	<tr valign="top">
	  <th scope="row">
		<label for="<?php echo $dom_id; ?>"><?php echo $field['title']; ?></label>
	  </th>
			<td>
				<input type="text" id="<?php echo $dom_id; ?>" name="<?php echo $field_id; ?>" value="<?php echo esc_attr( self::$field_id ); ?>"
				  <?php
					if ( self::$input_text_width ) {
						printf( ' style="width: %s"', self::$input_text_width ); }
					?>
				>
			<?php if ( $field['description'] ) { ?>
				<p class="description"><?php echo $field['description']; ?></p>
				<?php } ?>
			</td>
		</tr>
			<?php
		}
		?>
		</table>
	<p class="submit">
	  <input type="submit" value="Save Settings" class="button-primary">
	</p>
	</form>
</div>
		<?php
	}
}
