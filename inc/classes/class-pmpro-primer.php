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
class PMPro_Primer {
	/**
	 * [init description]
	 *
	 * @return [type] [description]
	 */
	public static function init() {
		add_action( 'admin_menu', array( __CLASS__, 'pmpro_primer' ) );
		add_action( 'admin_enqueue_scripts', array( __CLASS__, 'pmpro_primer_enqueue' ) );
		// add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'pmpro_primer_action_links' );
		// add_filter( 'pmpro_menu_title', array( __CLASS__, 'pmpro_change_menu_name' ) );
	}

	/**
	 * Add a page to the primer menu.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public static function pmpro_primer() {
		global $this_menu_slug;
		$this_menu_slug = preg_replace( '/_+/', '-', __FUNCTION__ );
		$label = ucwords( preg_replace( '/_+/', ' ', __FUNCTION__ ) );
		// $pmpro_primer_screen = add_submenu_page( __( 'pmpro-membershiplevels', $label, 'pmpro-primer-menu' ), __( $label, 'pmpro-primer-menu' ), 'manage_options', $this_menu_slug . '.php', array( __CLASS__, 'pmpro_primer_page' ) );
		$pmpro_primer_screen = add_dashboard_page( __( $label, 'pmpro-primer-menu' ), __( $label, 'pmpro-primer-menu' ), 'manage_options', $this_menu_slug . '.php', array( __CLASS__, 'pmpro_primer_page' ) );
		// Adds my_help_tab when primer_page loads
		add_action( 'load-' . $pmpro_primer_screen, array( __CLASS__, 'add_primer_help_tab' ) );
	}

	public static function add_primer_help_tab() {
		$screen = get_current_screen();
		$help_1_id = 'using_pmpro_array';
		$help_1_title = ucwords( preg_replace( '/_+/', ' ', $help_1_id ) );
		$sample_output = self::get_sample_pmpro_levels_code();
		$sample_description = 'This is a quick sample of what you can do with the code on this page. Copy and paste this into your code to see how it works.';
		$screen->add_help_tab(
			array(
				'id'    => $help_1_id,
				'title' => __( $help_1_title ),
				'content'   => '<h4>' . __( $help_1_title ) . '</h4>' .
				'<p class="description">' . esc_html( $sample_description ) .
				'</p>' .
				'<xmp>
	$array = get_pmpro_levels_array();
	foreach ( $array as $key => $value ) {
		echo \'Level \' . $value[\'id\'] . \' is \' . $value[\'name\'] . \' costs $\' . $value[\'billing_amount\'] . \' <br> \';
	}
		</xmp>
		' . 'Returns this: <p>' . $sample_output . '</p>',
			)
		);
		$screen->add_help_tab(
			array(
				'id'    => 'primer_help_2',
				'title' => __( 'Primer Help 2' ),
				'content'   => '<p>' . __( 'Descriptive content that will show in My Help Tab-body goes here.' ) . '</p>',
			)
		);
		$screen->add_help_tab(
			array(
				'id'    => 'primer_help_3',
				'title' => __( 'Primer Help 3' ),
				'content'   => '<p>' . __( 'Descriptive content that will show in My Help Tab-body goes here.' ) . '</p>',
			)
		);
	}

	public static function pmpro_primer_enqueue() {
		wp_register_script(
			'basic-accordion',
			plugins_url( '/js/basic-accordion.js', dirname( __FILE__ ) ),
			array( 'jquery', 'jquery-ui-accordion' )
		);
		wp_register_style(
			'basic-accordion',
			plugins_url( '/css/basic-accordion.css', dirname( __FILE__ ) ),
			time()
		);
		wp_enqueue_style( 'basic-accordion' );
		wp_enqueue_script( 'basic-accordion' );
	}
	/**
	 * Debug Information
	 *
	 * @since 1.0.0
	 *
	 * @param bool $html Optional. Return as HTML or not
	 *
	 * @return
	 */
	public static function pmpro_primer_page() {
		global $level, $pmpro_levels;
		echo '<div class="wrap">';
		echo '<h2>' . ucwords( preg_replace( '/_+/', ' ', __FUNCTION__ ) ) . '</h2>';
		$screen = get_current_screen();
		?>
	<div id="primer-accordion">
		<h3>Accordion Headers are h3</h3>
			<p>Click on each to open and close the accordion.</p>

		<h3>get_pmpro_levels_object() code looks like:</h3>
			<div>
<pre>
global $wpdb;
$existing_levels = $wpdb->get_results(
	"
SELECT * FROM $wpdb->pmpro_membership_levels
"
);
return $existing_levels;
</pre>
			</div>
		<h3>get_pmpro_levels_object() returns:</h3>
			<div>
				<?php
					echo '<pre>';
					print_r( self::get_pmpro_levels_object() );
					echo '</pre>';
				?>
			</div>

		<h3>get_pmpro_levels_array()</h3>
			<div>
				<?php
					echo '<pre>';
					print_r( self::get_pmpro_levels_array() );
					echo '</pre>';
				?>
			</div>

		<h3>get_pmpro_levels_shortlist()</h3>
			<div>
				<?php
					echo '<pre>';
					print_r( self::get_pmpro_levels_shortlist() );
					echo '</pre>';
				?>
			</div>

		<?php

		echo '<h3>loop thru get_pmpro_levels_shortlist() returns</h3>';
		echo '<pre>';

		$array = self::get_pmpro_levels_array();
		foreach ( $array as $key => $value ) {
			echo 'Level ' . $value['id'] . ' is ' . $value['name'] . ' costs $' . $value['billing_amount'] . '<br>';
		}

		echo '</pre>';

		echo '</div>
		</div>';
	}
	/**
	 * Description
	 *
	 * @return object
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
	 * @return array
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

	public static function get_sample_pmpro_levels_code() {
		$array = self::get_pmpro_levels_array();
		foreach ( $array as $key => $value ) {
			$sample .= 'Level ' . $value['id'] . ' is ' . $value['name'] . ' costs $' . $value['billing_amount'] . '<br>';
		}
		return $sample;
	}

	/**
	 * Description
	 *
	 * @return string
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
	 * Function to add links to the plugin action links
	 *
	 * @param array $links Array of links to be shown in plugin action links.
	 */
	public static function pmpro_primer_action_links( $links ) {
		global $this_menu_slug;
		if ( current_user_can( 'manage_options' ) ) {
			$new_links = array(
				'<a href="' . get_admin_url( null, 'index.php?page=' . $this_menu_slug ) . '">' . __( 'View primer', 'pmpro-primer-menu' ) . '</a>',
			);
		}
		return array_merge( $new_links, $links );
	}
}
