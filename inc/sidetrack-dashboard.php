<?php
/**
 * Plugin Name: Sidetrack Dashboard
 *
 * Author: pbrocks
 * Text Domain: sidetrack-dashboard
 */

// include 'tabbed-settings.php';

/**
 * Add a page to the dashboard menu.
 *
 * @since 1.0.0
 *
 * @return array
 */
add_action( 'admin_menu', 'sidetrack_dashboard' );
add_action( 'init', 'sidetrack_admin_init' );
add_action( 'admin_footer', 'sidetrack_diagnostic_message' );
function sidetrack_dashboard() {
	global $sidetrack_dash;
	$slug = preg_replace( '/_+/', '-', __FUNCTION__ );
	$label = ucwords( preg_replace( '/_+/', ' ', __FUNCTION__ ) );
	$sidetrack_dash = add_dashboard_page( __( $label, 'sidetrack-dashboard' ), __( $label, 'sidetrack-dashboard' ), 'manage_options', $slug . '.php', 'sidetrack_dashboard_page' );
	// add_action( "load-{$sidetrack_dash}", 'sidetrack_tabbed_settings' );
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
function sidetrack_dashboard_page() {
	global $pmpro_levels;
	echo '<div class="wrap">';
	echo '<h2>' . ucwords( preg_replace( '/_+/', ' ', __FUNCTION__ ) ) . '</h2>';
	$screen = get_current_screen();
	echo '<h4 style="color:rgba(250,128,114,.7);">Current Screen is <span style="color:rgba(250,128,114,1);">' . $screen->id . '</span></h4>';

	// Let's grab the tabs that we created in the `tabbed-settings.php`
	// sidetrack_admin_tabs( $current = 'homepage' );
	sidetrack_tabbed_settings();
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
function sidetrack_admin_init() {
	$sidetrack_settings = get_option( 'sidetrack_tabbed_settings' );
	if ( empty( $sidetrack_settings ) ) {
		$sidetrack_settings = array(
			'sidetrack_intro' => 'Some intro text for the home page',
			'sidetrack_tag_class' => false,
			'sidetrack_code' => false,
		);
		add_option( 'sidetrack_tabbed_settings', $sidetrack_settings, '', 'yes' );
	}
}


function sidetrack_save_theme_settings() {
	global $pagenow;
	$sidetrack_settings = get_option( 'sidetrack_tabbed_settings' );
	if ( $pagenow == 'index.php' && $_GET['page'] == 'sidetrack-dashboard.php' ) {
		if ( isset( $_GET['tab'] ) ) {
			$tab = $_GET['tab'];
		} else {
			$tab = 'homepage';
		}

		switch ( $tab ) {
			case 'general':
				$sidetrack_settings['sidetrack_tag_class']    = $_POST['sidetrack_tag_class'];
				break;
			case 'footer':
				$sidetrack_settings['sidetrack_code']  = $_POST['sidetrack_code'];
				break;
			case 'homepage':
				$sidetrack_settings['sidetrack_intro']    = $_POST['sidetrack_intro'];
				break;
		}
	}

	if ( ! current_user_can( 'unfiltered_html' ) ) {
		if ( $sidetrack_settings['sidetrack_code'] ) {
			$sidetrack_settings['sidetrack_code'] = stripslashes( esc_textarea( wp_filter_post_kses( $sidetrack_settings['sidetrack_code'] ) ) );
		}
		if ( $sidetrack_settings['sidetrack_intro'] ) {
			$sidetrack_settings['sidetrack_intro'] = stripslashes( esc_textarea( wp_filter_post_kses( $sidetrack_settings['sidetrack_intro'] ) ) );
		}
	}

	$updated = update_option( 'sidetrack_tabbed_settings', $sidetrack_settings );
}

function sidetrack_diagnostic_message() {
	global $current_user;
	echo '<div id="lpv-head"><div>$_GET <pre>';
	print_r( $_GET );
	echo '</pre></div>';
	echo '<div>$_REQUEST <pre>';
	print_r( $_REQUEST );
	echo '</pre></div>';
	echo '<div>$_POST <pre>';
	print_r( $_POST );
	add_action( 'template_redirect', 'pmpromc_processSubscriptions', 1 );
	add_filter( 'wp_redirect', 'pmpromc_processSubscriptions', 99 );
	add_action( 'pmpro_membership_post_membership_expiry', 'pmpromc_processUnsubscriptions' );
	echo '</pre></div><div>';
	echo __FUNCTION__;
	echo '<br>Line ' . __LINE__ . '</div></div>';

	// $message = '<h3 id="lpv-head"><span id="lpv_counter"></span>' . $xyz . ' <br> ' . $stg . '</h3>';
	// if ( current_user_can( 'manage_options' ) ) {
	// echo $message;
	// }
}




function sidetrack_admin_tabs( $current = 'homepage' ) {
	$tabs = array(
		'homepage' => 'Home',
		'general' => 'General',
		'settings' => 'Settings',
		'footer' => 'Foot333er',
	);
	$links = array();
	echo '<div id="icon-themes" class="icon32"><br></div>';
	echo '<h2 class="nav-tab-wrapper">';
	foreach ( $tabs as $tab => $name ) {
		$class = ( $tab == $current ) ? ' nav-tab-active' : '';
		echo "<a class='nav-tab$class' href='?page=sidetrack-dashboard.php&tab=$tab'>$name</a>";

	}
	echo '</h2>';
}

function sidetrack_tabbed_settings() {
	global $pagenow;
	$sidetrack_settings = get_option( 'sidetrack_tabbed_settings' );
	if ( 'true' == esc_attr( $_GET['updated'] ) ) {
		echo '<div class="updated" ><p> Settings updated.</p></div>';
	}

	if ( isset( $_GET['tab'] ) ) {
		sidetrack_admin_tabs( $_GET['tab'] );
	} else {
		sidetrack_admin_tabs( 'homepage' );
	}
	?>
<style type="text/css">
	#lpv-head {
		text-align: center;
	}
	.tab-content-wrapper {
		background: aliceblue;
		padding: 1rem;
	}
</style>
<div id="poststuff">
	<div class="tab-content-wrapper">
		<form method="post" action="<?php admin_url( 'index.php?page=sidetrack-dashboard.php' ); ?>">
			<?php
			// wp_nonce_field( 'sidetrack-settings-page' );
			if ( $pagenow == 'index.php' && $_GET['page'] == 'sidetrack-dashboard.php' ) {

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
							<th><label for="sidetrack_tag_class">Tags with CSS classes:</label></th>
							<td>
								<input id="sidetrack_tag_class" name="sidetrack_tag_class" type="checkbox" 
								<?php
								if ( $sidetrack_settings['sidetrack_tag_class'] ) {
									echo 'checked="checked"';}
								?>
								 value="true" /> 
								<span class="description">Output each post tag with a specific CSS class using its slug.</span>
							</td>
						</tr>
						<?php
						break;
					case 'settings':
						?>
						<tr>
							<th><label for="sidetrack_settings">$sidetrack_settings:</label></th>
							<td>
							<pre>
								<?php
								print_r( $sidetrack_settings );
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
							<th><label for="sidetrack_code">Insert tracking code:</label></th>
							<td>
								<textarea id="sidetrack_code" name="sidetrack_code" cols="60" rows="5"><?php echo esc_html( stripslashes( $sidetrack_settings['sidetrack_code'] ) ); ?></textarea><br/>
								<span class="description">Enter your Google Analytics tracking code:</span>
							</td>
						</tr>
						<?php
						break;
					case 'homepage':
						?>
						<tr>
							<th><label for="sidetrack_intro">Introduction</label></th>
							<td>
								<textarea id="sidetrack_intro" name="sidetrack_intro" cols="60" rows="5" ><?php echo esc_html( stripslashes( $sidetrack_settings['sidetrack_intro'] ) ); ?></textarea><br/>
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
						<input type="hidden" name="sidetrack-settings-submit" value="Y" />
					</p>
			</form>
		</div>
	</div>
</div>
	<?php
}

