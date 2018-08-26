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
class PMPro_Menu_Adjustments {
	/**
	 * [init description]
	 *
	 * @return [type] [description]
	 */
	public static function init() {
		// add_action( 'admin_menu', array( __CLASS__, 'add_pmpro_sortable_list_page' ) );
	}

	/**
	 * Menu item will allow us to load the page to display the table
	 */
	public static function add_pmpro_sortable_list_page() {
		$hook_suffix = add_submenu_page( 'pmpro-membershiplevels', 'Sortable Members', 'Sortable Members', 'manage_options', 'pmpro-list-table.php', array( __CLASS__, 'list_table_page' ) );
		$admin_menu = add_submenu_page( 'pmpro-membershiplevels', 'Add Members', 'Add Members', 'manage_options', 'pmpro-add-table.php', array( __CLASS__, 'list_table_page' ) );
		add_action( "load-$hook_suffix", array( __CLASS__, 'member_screen_options' ) );
		add_action( "load-$hook_suffix", array( __CLASS__, 'sortable_help_tabs' ) );
	}

	public static function add_member_admin_page() {
		?>
			<div class="wrap">
				<div id="icon-users" class="icon32"></div>
				<h2>PMPro Add Member Add</h2>
				<p><?php echo __FUNCTION__ . ' in<br> ' . __FILE__; ?></p>
			</div><!-- .wrap -->
		<?php
	}

	public static function member_screen_options() {
		global $pmpro_sortable_list;
		$option = 'per_page';
		$args = array(
			'label' => 'Subscribers',
			'default' => 10,
			'option' => 'members_per_page',
		);
		add_screen_option( $option, $args );
		$pmpro_sortable_list = new PMPro_Member_List();
	}
	public static function add_help_menu() {
		$screen = get_current_screen();
		if ( $screen->id != 'pmpro-list-table.php' ) {
			return;
		}
		$screen->add_help_tab(
			array(
				'id'      => 'reveal_slide_reordering_help_tab',
				'title'   => __( 'PMPro Member List', 'pmpro-sortable-members' ),
				'content' => '<p>' . __( ' To reposition an item, simply drag and drop the row by "clicking and holding" it anywhere (outside of the links and form controls) and moving it to its new position.', 'pmpro-sortable-members' ) . '</p>',
			)
		);
	}
	public static function sortable_help_tabs() {
		$screen = get_current_screen();
		$screen->add_help_tab(
			array(
				'id'      => 'sortable_overview',
				'title'   => __( 'Sortable Overview', 'pmpro-sortable-members' ),
				'content' => '<p>' . __( 'Overview of your plugin or theme here', 'pmpro-sortable-members' ) . '</p>',
			)
		);

		$screen->add_help_tab(
			array(
				'id'      => 'sortable_faq',
				'title'   => __( 'Sortable FAQ', 'pmpro-sortable-members' ),
				'content' => '<p>' . __( 'Frequently asked questions and their answers here', 'pmpro-sortable-members' ) . '</p>',
			)
		);

		$screen->add_help_tab(
			array(
				'id'      => 'sortable_support',
				'title'   => __( 'Sortable Support', 'pmpro-sortable-members' ),
				'content' => '<p>' . __( 'For support, visit the <a href="https://www.paidmembershipspro.com/forums/forum/members-forum/" target="_blank">Support Forums</a>', 'pmpro-sortable-members' ) . '</p>',
			)
		);

		$screen->set_help_sidebar( '<p>' . __( 'This is the content you will be adding to the sidebar.', 'pmpro-sortable-members' ) . '</p>' );
	}

	/**
	 * Display the list table page
	 *
	 * @return Void
	 */
	public static function list_table_page() {
		?>
			<div class="wrap">
				<div id="icon-users" class="icon32"></div>
				<h2>PMPro Sortable Member List</h2>
			</div>
		<?php
	}

}
