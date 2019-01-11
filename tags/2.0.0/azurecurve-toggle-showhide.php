<?php
/*
Plugin Name: azurecurve Toggle Show/Hide
Plugin URI: http://development.azurecurve.co.uk/plugins/toggle-show-hide
Description: Toggle to show or hide a section of content
Version: 2.0.0
Author: azurecurve
Author URI: http://development.azurecurve.co.uk

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.

The full copy of the GNU General Public License is available here: http://www.gnu.org/licenses/gpl.txt

*/

add_shortcode( 'toggle', 'azc_toggle_show_hide' );

add_action('wp_enqueue_scripts', 'azc_tsh_load_css');
add_action('wp_enqueue_scripts', 'azc_tsh_load_jquery');

function azc_tsh_load_css(){
	wp_register_style( 'azc-tsh', plugins_url( 'style.css', __FILE__ ), '', '1.0.0' );
	wp_enqueue_style( 'azc-tsh', plugins_url( 'style.css', __FILE__ ), '', '1.0.0' );
	$options = get_option('azc_tsh_options');
	if (!isset($options['image_open'])){ $options['image_open'] = ''; }
	if (!isset($options['image_close'])){ $options['image_close'] = ''; }
	$custom_css = "";
	if (strlen($options['image_close']) > 0){
		$custom_css .= '.azc_tsh_toggle_active {
							background-image: url('.plugins_url( 'images/'.$options['image_close'], __FILE__ ).') !important;
						}
						.azc_tsh_toggle_open_active {
							background-image: url('.plugins_url( 'images/'.$options['image_close'], __FILE__ ).');
						}
						';
	}
	if (strlen($options['image_open']) > 0){
		$custom_css .= '.azc_tsh_toggle {
							background-image: url('.plugins_url( 'images/'.$options['image_open'], __FILE__ ).');
						}
						.azc_tsh_toggle_open {
							background-image: url('.plugins_url( 'images/'.$options['image_open'], __FILE__ ).') !important;
						}
						';
	}
	if (strlen($options['title_font']) > 0){
		$custom_css .= '.azc_tsh_toggle, .azc_tsh_toggle_open, .azc_tsh_toggle_active, .azc_tsh_toggle_open_active{
							font-family: '.$options['title_font'].';
						}';
	}
	if (strlen($options['title_font_size']) > 0){
		$custom_css .= '.azc_tsh_toggle, .azc_tsh_toggle_open, .azc_tsh_toggle_active, .azc_tsh_toggle_open_active{
							font-size: '.$options['title_font_size'].';
						}';
	}
	if (strlen($options['text_font']) > 0){
		$custom_css .= '.azc_tsh_toggle_container, .azc_tsh_toggle_container_open{
							font-family: '.$options['text_font'].';
						}';
	}
	if (strlen($options['text_font_size']) > 0){
		$custom_css .= '.azc_tsh_toggle_container, .azc_tsh_toggle_container_open{
							font-size: '.$options['text_font_size'].';
						}';
	}
	if (strlen($custom_css) > 0){
		wp_add_inline_style( 'azc-tsh', $custom_css );
	}
}

function azc_tsh_load_jquery(){
	wp_enqueue_script( 'azc-tsh', plugins_url('jquery.js', __FILE__), array('jquery'), '3.9.1');
}

function azc_toggle_show_hide($atts, $content = null) {
	$options = get_option( 'azc_tsh_options' );
	if ($options['use_multisite'] == 1){
		$options = get_site_option( 'azc_tsh_options' );
	}
	
	if (strlen(stripslashes($options['title'])) == 0){
		$title = 'Click to show/hide';
	}else{
		$title = $options['title'];
	}
	
	// extract attributes from shortcode
	extract(shortcode_atts(array(
		'title' => stripslashes($title),
		'title_color' => stripslashes($options['title_color']),
		'title_font' => stripslashes($options['title_font']),
		'title_font_size' => stripslashes($options['title_font_size']),
		'title_font_weight' => stripslashes($options['title_font_weight']),
		'expand' => 0,
		'border' => stripslashes($options['border']),
		'bgtitle' => stripslashes($options['bg_title']),
		'bgtext' => stripslashes($options['bg_text']),
		'text_color' => stripslashes($options['text_color']),
		'text_font' => stripslashes($options['text_font']),
		'text_font_size' => stripslashes($options['text_font_size']),
		'text_font_weight' => stripslashes($options['text_font_weight']),
		'disable_image' => $options['disable_image'],
		'width' => stripslashes($options['width']),
	), $atts));
	
	$border_style='';
	$link_style='';
	if($expand == 1){
		$expand = '_open';
		$expand_active = $expand.'_active';
	}else{
		$expand = '';
		$expand_active = '';
	}
	if (strlen($border) > 0){ $border = "border: $border; "; }
	if (strlen($title_color) > 0){ $title_color = "color: $title_color; "; }
	if (strlen($title_font) > 0){ $title_font = "font-family: $title_font; "; }
	if (strlen($title_font_size) > 0){ $title_font_size = "font-size: $title_font_size; "; }
	if (strlen($title_font_weight) > 0){ $title_font_weight = "font-weight: $title_font_weight; "; }
	if (strlen($bgtitle) > 0){ $background_title = "background-color: $bgtitle; "; }
	if (strlen($bgtext) > 0){ $background_text = "background-color: $bgtext; "; }
	if (strlen($text_color) > 0){ $text_color = "color: $text_color; "; }
	if (strlen($text_font) > 0){ $text_font = "font-family: $text_font; "; }
	if (strlen($text_font_size) > 0){ $text_font_size = "font-size: $text_font_size; "; }
	if (strlen($text_font_weight) > 0){ $text_font_weight = "font-weight: $text_font_weight; "; }
	if ($disable_image == 1){
		$disable_image = 'background-image: none !important; padding-left: 10px; ';
	}else{
		$disable_image = '';
	}
	if (strlen($width) > 0){ $width = "margin: auto; width: $width; "; }
	if($options['allow_shortcodes'] == 1){
		$title = do_shortcode($title);
		$content = do_shortcode($content);
	}
	
	$output = "<h3 class='azc_tsh_toggle$expand_active' style='$border$background_title$disable_image'><a href='#' style='$title_color$title_font$title_font_size$title_font_weight'>$title</a></h3>";
	$output .= "<div class='azc_tsh_toggle_container$expand' style='$border$background_text$text_color$text_font$text_font_size$text_font_weight'>$content</div>";
	if (strlen($width) > 0){
		$output = '<div style="'.$width.'">'.$output.'</div>';
	}
	return $output;
}

function azc_tsh_load_plugin_textdomain(){
	
	$loaded = load_plugin_textdomain( 'azc-tsh', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}
add_action('plugins_loaded', 'azc_tsh_load_plugin_textdomain');


function azc_tsh_set_default_options($networkwide) {
	
	$new_options = array(
				'use_multisite' => 0
				,'border' => ''
				,'title' => ''
				,'title_color' => ''
				,'title_font' => ''
				,'title_font_size' => ''
				,'title_font_weight' => ''
				,'allow_shortcodes' => 0
				,'bg_title' => ''
				,'bg_text' => ''
				,'text_color' => ''
				,'disable_image' => 0
				,'width' => ""
				,'image_open' => ""
				,'image_close' => ""
				,'image_open' => ""
				,'image_close' => ""
			);
	
	// set defaults for multi-site
	if (function_exists('is_multisite') && is_multisite()) {
		// check if it is a network activation - if so, run the activation function for each blog id
		if ($networkwide) {
			global $wpdb;

			$blog_ids = $wpdb->get_col( "SELECT blog_id FROM $wpdb->blogs" );
			$original_blog_id = get_current_blog_id();

			foreach ( $blog_ids as $blog_id ) {
				switch_to_blog( $blog_id );

				if ( get_option( 'azc_tsh_options' ) === false ) {
					add_option( 'azc_tsh_options', $new_options );
				}
			}

			switch_to_blog( $original_blog_id );
		}else{
			if ( get_option( 'azc_tsh_options' ) === false ) {
				add_option( 'azc_tsh_options', $new_options );
			}
		}
		if ( get_site_option( 'azc_tsh_options' ) === false ) {
			add_site_option( 'azc_tsh_options', $new_options );
		}
	}
	//set defaults for single site
	else{
		if ( get_option( 'azc_tsh_options' ) === false ) {
			add_option( 'azc_tsh_options', $new_options );
		}
	}
}
register_activation_hook( __FILE__, 'azc_tsh_set_default_options' ); 

function azc_tsh_plugin_action_links($links, $file) {
    static $this_plugin;

    if (!$this_plugin) {
        $this_plugin = plugin_basename(__FILE__);
    }

    if ($file == $this_plugin) {
        $settings_link = '<a href="' . get_bloginfo('wpurl') . '/wp-admin/admin.php?page=azc-tsh">Settings</a>';
        array_unshift($links, $settings_link);
    }

    return $links;
}
add_filter('plugin_action_links', 'azc_tsh_plugin_action_links', 10, 2);

/*
function azc_tsh_settings_menu() {
	add_options_page( 'azurecurve Toggle Show/Hide',
	'azurecurve Toggle Show/Hide', 'manage_options',
	'azurecurve-toggle-showhide', 'azc_tsh_config_page' );
}
add_action( 'admin_menu', 'azc_tsh_settings_menu' );
*/

function azc_tsh_settings() {
	if (!current_user_can('manage_options')) {
        wp_die(__('You do not have sufficient permissions to access this page.', 'azc-tsh'));
    }
	
	// Retrieve plugin configuration options from database
	$options = get_option( 'azc_tsh_options' );
	if (!isset($options['image_open'])){ $options['image_open'] = ''; }
	if (!isset($options['image_close'])){ $options['image_close'] = ''; }
	?>
	<div id="azc-tsh-general" class="wrap">
		<fieldset>
			<h2>azurecurve Toggle Show/Hide <?php _e('Settings', 'azc-tsh'); ?></h2>
			<?php if( isset($_GET['settings-updated']) ) { ?>
				<div id="message" class="updated">
					<p><strong><?php _e('Settings have been saved.') ?></strong></p>
				</div>
			<?php } ?>
			<form method="post" action="admin-post.php">
				<input type="hidden" name="action" value="save_azc_tsh_options" />
				<input name="page_options" type="hidden" value="tsh_suffix" />
				
				<!-- Adding security through hidden referrer field -->
				<?php wp_nonce_field( 'azc-tsh' ); ?>
				<table class="form-table">
				<tr><td colspan=2>
					<p><?php _e('If the options are blank then the defaults in the plugin\'s CSS will be used.', 'azc-tsh'); ?></p>
				</td></tr>
				<?php if (function_exists('is_multisite') && is_multisite()) { ?>
					<tr><th scope="row">Use multisite options instead of the site options below?</th><td>
						<fieldset><legend class="screen-reader-text"><span><?php _e('Disable images in toggle title?', 'azc-tsh'); ?></span></legend>
						<label for="use_multisite"><input name="use_multisite" type="checkbox" id="use_multisite" value="1" <?php checked( '1', $options['use_multisite'] ); ?> /></label>
						</fieldset>
					</td></tr>
				<?php } ?>
				<tr><th scope="row"><label for="title"><?php _e('Title', 'azc-tsh'); ?></label></th><td>
					<input type="text" name="title" value="<?php echo esc_html( stripslashes($options['title']) ); ?>" class="large-text" />
					<p class="description"><?php _e('Set default title text (e.g. Click here to toggle show/hide)', 'azc-tsh'); ?></p>
				</td></tr>
				<tr><th scope="row"><label for="width"><?php _e('Width', 'azc-tsh'); ?></label></th><td>
					<input type="text" name="width" value="<?php echo esc_html( stripslashes($options['width']) ); ?>" class="large-text" />
					<p class="description"><?php _e('Set default width (e.g. 65% or 500px)', 'azc-tsh'); ?></p>
				</td></tr>
				<tr><th scope="row"><label for="border"><?php _e('Border', 'azc-tsh'); ?></label></th><td>
					<input type="text" name="border" value="<?php echo esc_html( stripslashes($options['border']) ); ?>" class="large-text" />
					<p class="description"><?php _e('Set default border (e.g. 1px solid #00F000)', 'azc-tsh'); ?></p>
				</td></tr>
				<tr><th scope="row"><label for="title_color"><?php _e('Title Color', 'azc-tsh'); ?></label></th><td>
					<input type="text" name="title_color" value="<?php echo esc_html( stripslashes($options['title_color']) ); ?>" class="large-text" />
					<p class="description"><?php _e('Set default title color (e.g. #000)', 'azc-tsh'); ?></p>
				</td></tr>
				<tr><th scope="row"><label for="bg_title"><?php _e('Title Background Color', 'azc-tsh'); ?></label></th><td>
					<input type="text" name="bg_title" value="<?php echo esc_html( stripslashes($options['bg_title']) ); ?>" class="large-text" />
					<p class="description"><?php _e('Set default title background color (e.g. 1px solid #00F000)', 'azc-tsh'); ?></p>
				</td></tr>
				<tr><th scope="row"><label for="title_font"><?php _e('Title Font Family', 'azc-tsh'); ?></label></th><td>
					<input type="text" name="title_font" value="<?php echo esc_html( stripslashes($options['title_font']) ); ?>" class="large-text" />
					<p class="description"><?php _e('Set default title font family (e.g. Arial, Calibri)', 'azc-tsh'); ?></p>
				</td></tr>
				<tr><th scope="row"><label for="title_font_size"><?php _e('Title Font Size', 'azc-tsh'); ?></label></th><td>
					<input type="text" name="title_font_size" value="<?php echo esc_html( stripslashes($options['title_font_size']) ); ?>" class="large-text" />
					<p class="description"><?php _e('Set default title font size (e.g. 1.2em or 14px)', 'azc-tsh'); ?></p>
				</td></tr>
				<tr><th scope="row"><label for="title_font_weight"><?php _e('Title Font Weight', 'azc-tsh'); ?></label></th><td>
					<input type="text" name="title_font_weight" value="<?php echo esc_html( stripslashes($options['title_font_weight']) ); ?>" class="large-text" />
					<p class="description"><?php _e('Set default title font weight (e.g. 600)', 'azc-tsh'); ?></p>
				</td></tr>
				<tr><th scope="row"><label for="text_color"><?php _e('Text Color', 'azc-tsh'); ?></label></th><td>
					<input type="text" name="text_color" value="<?php echo esc_html( stripslashes($options['text_color']) ); ?>" class="large-text" />
					<p class="description"><?php _e('Set default text color (e.g. #000)', 'azc-tsh'); ?></p>
				</td></tr>
				<tr><th scope="row"><label for="bg_text"><?php _e('Text Background Color', 'azc-tsh'); ?></label></th><td>
					<input type="text" name="bg_text" value="<?php echo esc_html( stripslashes($options['bg_text']) ); ?>" class="large-text" />
					<p class="description"><?php _e('Set default bg_text (e.g. 1px solid #00F000)', 'azc-tsh'); ?></p>
				</td></tr>
				<tr><th scope="row"><label for="text_font"><?php _e('Text Font Family', 'azc-tsh'); ?></label></th><td>
					<input type="text" name="text_font" value="<?php echo esc_html( stripslashes($options['text_font']) ); ?>" class="large-text" />
					<p class="description"><?php _e('Set default text font family (e.g. Arial, Calibri)', 'azc-tsh'); ?></p>
				</td></tr>
				<tr><th scope="row"><label for="text_font_size"><?php _e('Text Font Size', 'azc-tsh'); ?></label></th><td>
					<input type="text" name="text_font_size" value="<?php echo esc_html( stripslashes($options['text_font_size']) ); ?>" class="large-text" />
					<p class="description"><?php _e('Set default text font size (e.g. 1.2em or 14px)', 'azc-tsh'); ?></p>
				</td></tr>
				<tr><th scope="row"><label for="text_font_weight"><?php _e('Text Font Weight', 'azc-tsh'); ?></label></th><td>
					<input type="text" name="text_font_weight" value="<?php echo esc_html( stripslashes($options['text_font_weight']) ); ?>" class="large-text" />
					<p class="description"><?php _e('Set default text font weight (e.g. 600)', 'azc-tsh'); ?></p>
				</td></tr>
				<tr><th scope="row">Image Open</th><td style='background: #D3D3D3;'>
				<?php
				$dir = plugin_dir_path(__FILE__) . '/images';
				if (is_dir( $dir )) {
					if ($directory = opendir($dir)) {
						while (($file = readdir($directory)) !== false) {
							if ($file != '.' and $file != '..' and $file != 'Thumbs.db'){
								echo "<input type='radio' name='image_open' value='$file' "?><?php checked( $file, $options['image_open'] )?><?php echo ">&nbsp;<img src='".plugin_dir_url( __FILE__ )."images/".$file."' name='$file' alt='$file' />&nbsp;&nbsp;";
							}
						}
						closedir($directory);
					}
				}
				?>
				</td></tr>
				<tr><th scope="row">Image Close</th><td style='background: #D3D3D3;'>
				<?php
				$dir = plugin_dir_path(__FILE__) . '/images';
				if (is_dir( $dir )) {
					if ($directory = opendir($dir)) {
						while (($file = readdir($directory)) !== false) {
							if ($file != '.' and $file != '..' and $file != 'Thumbs.db'){
								echo "<input type='radio' name='image_close' value='$file' "?><?php checked( $file, $options['image_close'] )?><?php echo ">&nbsp;<img src='".plugin_dir_url( __FILE__ )."images/".$file."' alt='$file' />&nbsp;&nbsp;";
							}
						}
						closedir($directory);
					}
				}
				?>
				</td></tr>
				<tr><th scope="row">Disable images?</th><td>
					<fieldset><legend class="screen-reader-text"><span><?php _e('Disable images in toggle title?', 'azc-tsh'); ?></span></legend>
					<label for="disable_image"><input name="disable_image" type="checkbox" id="disable_image" value="1" <?php checked( '1', $options['disable_image'] ); ?> /><?php _e('Disable images in toggle title? Override setting by putting disable_image=0 in toggle.', 'azc-tsh'); ?></label>
					</fieldset>
				</td></tr>
				<tr><th scope="row">Allow Shortcodes?</th><td>
					<fieldset><legend class="screen-reader-text"><span><?php _e('Allow shortcodes within toggle?', 'azc-tsh'); ?></span></legend>
					<label for="allow_shortcodes"><input name="allow_shortcodes" type="checkbox" id="allow_shortcodes" value="1" <?php checked( '1', $options['allow_shortcodes'] ); ?> /><?php _e('Allow shortcodes within toggle?', 'azc-tsh'); ?></label>
					</fieldset>
				</td></tr>
				</table>
				<input type="submit" value="Submit" class="button-primary"/>
			</form>
		</fieldset>
	</div>
<?php }


function azc_tsh_admin_init() {
	add_action( 'admin_post_save_azc_tsh_options', 'process_azc_tsh_options' );
}
add_action( 'admin_init', 'azc_tsh_admin_init' );

function process_azc_tsh_options() {
	// Check that user has proper security level
	if ( !current_user_can( 'manage_options' ) ){
		wp_die( __('You do not have permissions for this action', 'azc-tsh'));
	}
	// Check that nonce field created in configuration form is present
	check_admin_referer( 'azc-tsh' );
	settings_fields('azc-tsh');
	
	// Retrieve original plugin options array
	$options = get_option( 'azc_tsh_options' );
	
	$option_name = 'use_multisite';
	if ( isset( $_POST[$option_name] ) ) {
		$options[$option_name] = 1;
	}else{
		$options[$option_name] = 0;
	}
	
	$option_name = 'border';
	if ( isset( $_POST[$option_name] ) ) {
		$options[$option_name] = ($_POST[$option_name]);
	}
	
	$option_name = 'title';
	if ( isset( $_POST[$option_name] ) ) {
		$options[$option_name] = ($_POST[$option_name]);
	}
	
	$option_name = 'title_color';
	if ( isset( $_POST[$option_name] ) ) {
		$options[$option_name] = ($_POST[$option_name]);
	}
	
	$option_name = 'bg_title';
	if ( isset( $_POST[$option_name] ) ) {
		$options[$option_name] = ($_POST[$option_name]);
	}
	
	$option_name = 'title_font';
	if ( isset( $_POST[$option_name] ) ) {
		$options[$option_name] = ($_POST[$option_name]);
	}
	
	$option_name = 'title_font_size';
	if ( isset( $_POST[$option_name] ) ) {
		$options[$option_name] = ($_POST[$option_name]);
	}
	
	$option_name = 'title_font_weight';
	if ( isset( $_POST[$option_name] ) ) {
		$options[$option_name] = ($_POST[$option_name]);
	}
	
	$option_name = 'bg_text';
	if ( isset( $_POST[$option_name] ) ) {
		$options[$option_name] = ($_POST[$option_name]);
	}
	
	$option_name = 'text_color';
	if ( isset( $_POST[$option_name] ) ) {
		$options[$option_name] = ($_POST[$option_name]);
	}
	
	$option_name = 'text_font';
	if ( isset( $_POST[$option_name] ) ) {
		$options[$option_name] = ($_POST[$option_name]);
	}
	
	$option_name = 'text_font_size';
	if ( isset( $_POST[$option_name] ) ) {
		$options[$option_name] = ($_POST[$option_name]);
	}
	
	$option_name = 'text_font_weight';
	if ( isset( $_POST[$option_name] ) ) {
		$options[$option_name] = ($_POST[$option_name]);
	}
	
	$option_name = 'width';
	if ( isset( $_POST[$option_name] ) ) {
		$options[$option_name] = ($_POST[$option_name]);
	}
	
	$option_name = 'image_open';
	if ( isset( $_POST[$option_name] ) ) {
		$options[$option_name] = ($_POST[$option_name]);
	}
	
	$option_name = 'image_close';
	if ( isset( $_POST[$option_name] ) ) {
		$options[$option_name] = ($_POST[$option_name]);
	}
	
	$option_name = 'disable_image';
	if ( isset( $_POST[$option_name] ) ) {
		$options[$option_name] = 1;
	}else{
		$options[$option_name] = 0;
	}
	
	$option_name = 'allow_shortcodes';
	if ( isset( $_POST[$option_name] ) ) {
		$options[$option_name] = 1;
	}else{
		$options[$option_name] = 0;
	}
	
	// Store updated options array to database
	update_option( 'azc_tsh_options', $options );
	
	// Redirect the page to the configuration form that was processed
	wp_redirect( add_query_arg( 'page', 'azc-tsh&settings-updated', admin_url( 'admin.php' ) ) );
	exit;
}


function add_azc_tsh_network_settings_page() {
	if (function_exists('is_multisite') && is_multisite()) {
		add_submenu_page(
			'settings.php',
			'azurecurve Toggle Show/Hide Settings',
			'azurecurve Toggle Show/Hide',
			'manage_network_options',
			'azc_tsh',
			'azc_tsh_network_settings_page'
			);
	}
}
add_action('network_admin_menu', 'add_azc_tsh_network_settings_page');

function azc_tsh_network_settings_page(){
	$options = get_site_option('azc_tsh_options');
	if (!isset($options['image_open'])){ $options['image_open'] = ''; }
	if (!isset($options['image_close'])){ $options['image_close'] = ''; }

	?>
	<div id="azc-tsh-general" class="wrap">
		<fieldset>
			<h2>azurecurve Toggle Show/Hide <?php _e('Network Settings', 'azc-tsh'); ?></h2>
			<?php if( isset($_GET['settings-updated']) ) { ?>
				<div id="message" class="updated">
					<p><strong><?php _e('Network Settings have been saved.') ?></strong></p>
				</div>
			<?php } ?>
			<form method="post" action="admin-post.php">
				<input type="hidden" name="action" value="save_azc_tsh_options" />
				<input name="page_options" type="hidden" value="suffix" />
				
				<!-- Adding security through hidden referrer field -->
				<?php wp_nonce_field( 'azc-tsh' ); ?>
				<table class="form-table">
				<tr><td colspan=2>
					<p><?php _e('If multisite is being used these options will be used when Use Multisite enabled in Site Options; if the options are blank the defaults in the plugin\'s CSS will be used.', 'azc-tsh'); ?></p>
				</td></tr>
				<tr><th scope="row"><label for="title"><?php _e('Title', 'azc-tsh'); ?></label></th><td>
					<input type="text" name="title" value="<?php echo esc_html( stripslashes($options['title']) ); ?>" class="large-text" />
					<p class="description"><?php _e('Set default title text (e.g. Click here to toggle show/hide)', 'azc-tsh'); ?></p>
				</td></tr>
				<tr><th scope="row"><label for="width"><?php _e('Width', 'azc-tsh'); ?></label></th><td>
					<input type="text" name="width" value="<?php echo esc_html( stripslashes($options['width']) ); ?>" class="large-text" />
					<p class="description"><?php _e('Set default width (e.g. 65% or 450px)', 'azc-tsh'); ?></p>
				</td></tr>
				<tr><th scope="row"><label for="border"><?php _e('Border', 'azc-tsh'); ?></label></th><td>
					<input type="text" name="border" value="<?php echo esc_html( stripslashes($options['border']) ); ?>" class="large-text" />
					<p class="description"><?php _e('Set default border (e.g. 1px solid #00F000)', 'azc-tsh'); ?></p>
				</td></tr>
				<tr><th scope="row"><label for="title_color"><?php _e('Title Color', 'azc-tsh'); ?></label></th><td>
					<input type="text" name="title_color" value="<?php echo esc_html( stripslashes($options['title_color']) ); ?>" class="large-text" />
					<p class="description"><?php _e('Set default title color (e.g. #000)', 'azc-tsh'); ?></p>
				</td></tr>
				<tr><th scope="row"><label for="bg_title"><?php _e('Title Background Color', 'azc-tsh'); ?></label></th><td>
					<input type="text" name="bg_title" value="<?php echo esc_html( stripslashes($options['bg_title']) ); ?>" class="large-text" />
					<p class="description"><?php _e('Set default title background color (e.g. 1px solid #00F000)', 'azc-tsh'); ?></p>
				</td></tr>
				<tr><th scope="row"><label for="title_font"><?php _e('Title Font Family', 'azc-tsh'); ?></label></th><td>
					<input type="text" name="title_font" value="<?php echo esc_html( stripslashes($options['title_font']) ); ?>" class="large-text" />
					<p class="description"><?php _e('Set default title font family (e.g. Arial, Calibri)', 'azc-tsh'); ?></p>
				</td></tr>
				<tr><th scope="row"><label for="title_font_size"><?php _e('Title Font Size', 'azc-tsh'); ?></label></th><td>
					<input type="text" name="title_font_size" value="<?php echo esc_html( stripslashes($options['title_font_size']) ); ?>" class="large-text" />
					<p class="description"><?php _e('Set default title font size (e.g. 1.2em or 14px)', 'azc-tsh'); ?></p>
				</td></tr>
				<tr><th scope="row"><label for="title_font_weight"><?php _e('Title Font Weight', 'azc-tsh'); ?></label></th><td>
					<input type="text" name="title_fotitle_font_weightnt" value="<?php echo esc_html( stripslashes($options['title_font_weight']) ); ?>" class="large-text" />
					<p class="description"><?php _e('Set default title font weight (e.g. 600)', 'azc-tsh'); ?></p>
				</td></tr>
				<tr><th scope="row"><label for="text_color"><?php _e('Text Color', 'azc-tsh'); ?></label></th><td>
					<input type="text" name="text_color" value="<?php echo esc_html( stripslashes($options['text_color']) ); ?>" class="large-text" />
					<p class="description"><?php _e('Set default title color (e.g. #000)', 'azc-tsh'); ?></p>
				</td></tr>
				<tr><th scope="row"><label for="bg_text"><?php _e('Text Background Color', 'azc-tsh'); ?></label></th><td>
					<input type="text" name="bg_text" value="<?php echo esc_html( stripslashes($options['bg_text']) ); ?>" class="large-text" />
					<p class="description"><?php _e('Set default bg_text (e.g. 1px solid #00F000)', 'azc-tsh'); ?></p>
				</td></tr>
				<tr><th scope="row"><label for="text_font"><?php _e('Text Font Family', 'azc-tsh'); ?></label></th><td>
					<input type="text" name="text_font" value="<?php echo esc_html( stripslashes($options['text_font']) ); ?>" class="large-text" />
					<p class="description"><?php _e('Set default text font family (e.g. Arial, Calibri)', 'azc-tsh'); ?></p>
				</td></tr>
				<tr><th scope="row"><label for="text_font_size"><?php _e('Text Font Size', 'azc-tsh'); ?></label></th><td>
					<input type="text" name="text_font_size" value="<?php echo esc_html( stripslashes($options['text_font_size']) ); ?>" class="large-text" />
					<p class="description"><?php _e('Set default text font size (e.g. 1.2em or 14px)', 'azc-tsh'); ?></p>
				</td></tr>
				<tr><th scope="row"><label for="text_font_weight"><?php _e('Text Font Weight', 'azc-tsh'); ?></label></th><td>
					<input type="text" name="text_font_weight" value="<?php echo esc_html( stripslashes($options['text_font_weight']) ); ?>" class="large-text" />
					<p class="description"><?php _e('Set default text font weight (e.g. 600)', 'azc-tsh'); ?></p>
				</td></tr>
				<tr><th scope="row">Image Open</th><td style='background: #D3D3D3;'>
				<?php
				$dir = plugin_dir_path(__FILE__) . '/images';
				if (is_dir( $dir )) {
					if ($directory = opendir($dir)) {
						while (($file = readdir($directory)) !== false) {
							if ($file != '.' and $file != '..' and $file != 'Thumbs.db'){
								echo "<input type='radio' name='image_open' value='$file' "?><?php checked( $file, $options['image_open'] )?><?php echo ">&nbsp;<img src='".plugin_dir_url( __FILE__ )."images/".$file."' name='$file' alt='$file' />&nbsp;&nbsp;";
							}
						}
						closedir($directory);
					}
				}
				?>
				</td></tr>
				<tr><th scope="row">Image Close</th><td style='background: #D3D3D3;'>
				<?php
				$dir = plugin_dir_path(__FILE__) . '/images';
				if (is_dir( $dir )) {
					if ($directory = opendir($dir)) {
						while (($file = readdir($directory)) !== false) {
							if ($file != '.' and $file != '..' and $file != 'Thumbs.db'){
								echo "<input type='radio' name='image_close' value='$file' "?><?php checked( $file, $options['image_close'] )?><?php echo ">&nbsp;<img src='".plugin_dir_url( __FILE__ )."images/".$file."' alt='$file' />&nbsp;&nbsp;";
							}
						}
						closedir($directory);
					}
				}
				?>
				</td></tr>
				<tr><th scope="row">Disable images?</th><td>
					<fieldset><legend class="screen-reader-text"><span><?php _e('Disable images in toggle title?', 'azc-tsh'); ?></span></legend>
					<label for="disable_image"><input name="disable_image" type="checkbox" id="disable_image" value="1" <?php checked( '1', $options['disable_image'] ); ?> /><?php _e('Disable images in toggle title? Override setting by putting disable_image=0 in toggle.', 'azc-tsh'); ?></label>
					</fieldset>
				</td></tr>
				<tr><th scope="row">Allow Shortcodes?</th><td>
					<fieldset><legend class="screen-reader-text"><span><?php _e('Allow shortcodes within toggle?', 'azc-tsh'); ?></span></legend>
					<label for="allow_shortcodes"><input name="allow_shortcodes" type="checkbox" id="allow_shortcodes" value="1" <?php checked( '1', $options['allow_shortcodes'] ); ?> /><?php _e('Allow shortcodes within toggle?', 'azc-tsh'); ?></label>
					</fieldset>
				</td></tr>
				</table>
				<input type="submit" value="Submit" class="button-primary" />
			</form>
		</fieldset>
	</div>
	<?php
}

function process_azc_tsh_network_options(){     
	if(!current_user_can('manage_network_options')) wp_die(_e('You do not have permissions to maintain these settings.'));
	check_admin_referer('azc-tsh');
	
	// Retrieve original plugin options array
	$options = get_site_option( 'azc_tsh_options' );

	$option_name = 'border';
	if ( isset( $_POST[$option_name] ) ) {
		$options[$option_name] = ($_POST[$option_name]);
	}

	$option_name = 'title';
	if ( isset( $_POST[$option_name] ) ) {
		$options[$option_name] = ($_POST[$option_name]);
	}

	$option_name = 'title_color';
	if ( isset( $_POST[$option_name] ) ) {
		$options[$option_name] = ($_POST[$option_name]);
	}

	$option_name = 'bg_title';
	if ( isset( $_POST[$option_name] ) ) {
		$options[$option_name] = ($_POST[$option_name]);
	}
	
	$option_name = 'title_font';
	if ( isset( $_POST[$option_name] ) ) {
		$options[$option_name] = ($_POST[$option_name]);
	}
	
	$option_name = 'title_font_size';
	if ( isset( $_POST[$option_name] ) ) {
		$options[$option_name] = ($_POST[$option_name]);
	}
	
	$option_name = 'title_font_weight';
	if ( isset( $_POST[$option_name] ) ) {
		$options[$option_name] = ($_POST[$option_name]);
	}
	
	$option_name = 'bg_text';
	if ( isset( $_POST[$option_name] ) ) {
		$options[$option_name] = ($_POST[$option_name]);
	}
	
	$option_name = 'text_color';
	if ( isset( $_POST[$option_name] ) ) {
		$options[$option_name] = ($_POST[$option_name]);
	}
	
	$option_name = 'text_font';
	if ( isset( $_POST[$option_name] ) ) {
		$options[$option_name] = ($_POST[$option_name]);
	}
	
	$option_name = 'text_font_size';
	if ( isset( $_POST[$option_name] ) ) {
		$options[$option_name] = ($_POST[$option_name]);
	}
	
	$option_name = 'text_font_weight';
	if ( isset( $_POST[$option_name] ) ) {
		$options[$option_name] = ($_POST[$option_name]);
	}
	
	$option_name = 'width';
	if ( isset( $_POST[$option_name] ) ) {
		$options[$option_name] = ($_POST[$option_name]);
	}
	
	$option_name = 'image_open';
	if ( isset( $_POST[$option_name] ) ) {
		$options[$option_name] = ($_POST[$option_name]);
	}
	
	$option_name = 'image_close';
	if ( isset( $_POST[$option_name] ) ) {
		$options[$option_name] = ($_POST[$option_name]);
	}
	
	$option_name = 'disable_image';
	if ( isset( $_POST[$option_name] ) ) {
		$options[$option_name] = 1;
	}else{
		$options[$option_name] = 0;
	}
	
	$option_name = 'allow_shortcodes';
	if ( isset( $_POST[$option_name] ) ) {
		$options[$option_name] = 1;
	}else{
		$options[$option_name] = 0;
	}
	
	update_site_option( 'azc_tsh_options', $options );

	wp_redirect(network_admin_url('settings.php?page=azurecurve-toggle-showhide&settings-updated'));
	exit;
}
add_action('network_admin_edit_update_azc_tsh_network_options', 'process_azc_tsh_network_options');


// azurecurve menu
if (!function_exists(azc_create_plugin_menu)){
	function azc_create_plugin_menu() {
		global $admin_page_hooks;
		
		if ( empty ( $admin_page_hooks['azc-menu-test'] ) ){
			add_menu_page( "azurecurve Plugins"
							,"azurecurve"
							,'manage_options'
							,"azc-plugin-menus"
							,"azc_plugin_menus"
							,plugins_url( '/images/Favicon-16x16.png', __FILE__ ) );
			add_submenu_page( "azc-plugin-menus"
								,"Plugins"
								,"Plugins"
								,'manage_options'
								,"azc-plugin-menus"
								,"azc_plugin_menus" );
		}
	}
	add_action("admin_menu", "azc_create_plugin_menu");
}

function azc_create_tsh_plugin_menu() {
	global $admin_page_hooks;
    
	add_submenu_page( "azc-plugin-menus"
						,"Toggle Show/Hide"
						,"Toggle Show/Hide"
						,'manage_options'
						,"azc-tsh"
						,"azc_tsh_settings" );
}
add_action("admin_menu", "azc_create_tsh_plugin_menu");

if (!function_exists(azc_plugin_index_load_css)){
	function azc_plugin_index_load_css(){
		wp_enqueue_style( 'azurecurve_plugin_index', plugins_url( 'pluginstyle.css', __FILE__ ) );
	}
	add_action('admin_head', 'azc_plugin_index_load_css');
}

if (!function_exists(azc_plugin_menus)){
	function azc_plugin_menus() {
		echo "<h3>azurecurve Plugins";
		
		echo "<div style='display: block;'><h4>Active</h4>";
		echo "<span class='azc_plugin_index'>";
		if ( is_plugin_active( 'azurecurve-bbcode/azurecurve-bbcode.php' ) ) {
			echo "<a href='admin.php?page=azc-bbcode' class='azc_plugin_index'>BBCode</a>";
		}
		if ( is_plugin_active( 'azurecurve-comment-validator/azurecurve-comment-validator.php' ) ) {
			echo "<a href='admin.php?page=azc-cv' class='azc_plugin_index'>Comment Validator</a>";
		}
		if ( is_plugin_active( 'azurecurve-conditional-links/azurecurve-conditional-links.php' ) ) {
			echo "<a href='admin.php?page=azc-cl' class='azc_plugin_index'>Conditional Links</a>";
		}
		if ( is_plugin_active( 'azurecurve-display-after-post-content/azurecurve-display-after-post-content.php' ) ) {
			echo "<a href='admin.php?page=azc-dapc' class='azc_plugin_index'>Display After Post Content</a>";
		}
		if ( is_plugin_active( 'azurecurve-filtered-categories/azurecurve-filtered-categories.php' ) ) {
			echo "<a href='admin.php?page=azc-fc' class='azc_plugin_index'>Filtered Categories</a>";
		}
		if ( is_plugin_active( 'azurecurve-flags/azurecurve-flags.php' ) ) {
			echo "<a href='admin.php?page=azc-f' class='azc_plugin_index'>Flags</a>";
		}
		if ( is_plugin_active( 'azurecurve-floating-featured-image/azurecurve-floating-featured-image.php' ) ) {
			echo "<a href='admin.php?page=azc-ffi' class='azc_plugin_index'>Floating Featured Image</a>";
		}
		if ( is_plugin_active( 'azurecurve-get-plugin-info/azurecurve-get-plugin-info.php' ) ) {
			echo "<a href='admin.php?page=azc-gpi' class='azc_plugin_index'>Get Plugin Info</a>";
		}
		if ( is_plugin_active( 'azurecurve-insult-generator/azurecurve-insult-generator.php' ) ) {
			echo "<a href='admin.php?page=azc-ig' class='azc_plugin_index'>Insult Generator</a>";
		}
		if ( is_plugin_active( 'azurecurve-mobile-detection/azurecurve-mobile-detection.php' ) ) {
			echo "<a href='admin.php?page=azc-md' class='azc_plugin_index'>Mobile Detection</a>";
		}
		if ( is_plugin_active( 'azurecurve-multisite-favicon/azurecurve-multisite-favicon.php' ) ) {
			echo "<a href='admin.php?page=azc-msf' class='azc_plugin_index'>Multisite Favicon</a>";
		}
		if ( is_plugin_active( 'azurecurve-page-index/azurecurve-page-index.php' ) ) {
			echo "<a href='admin.php?page=azc-pi' class='azc_plugin_index'>Page Index</a>";
		}
		if ( is_plugin_active( 'azurecurve-posts-archive/azurecurve-posts-archive.php' ) ) {
			echo "<a href='admin.php?page=azc-pa' class='azc_plugin_index'>Posts Archive</a>";
		}
		if ( is_plugin_active( 'azurecurve-rss-feed/azurecurve-rss-feed.php' ) ) {
			echo "<a href='admin.php?page=azc-rssf' class='azc_plugin_index'>RSS Feed</a>";
		}
		if ( is_plugin_active( 'azurecurve-rss-suffix/azurecurve-rss-suffix.php' ) ) {
			echo "<a href='admin.php?page=azc-rsss' class='azc_plugin_index'>RSS Suffix</a>";
		}
		if ( is_plugin_active( 'azurecurve-series-index/azurecurve-series-index.php' ) ) {
			echo "<a href='admin.php?page=azc-si' class='azc_plugin_index'>Series Index</a>";
		}
		if ( is_plugin_active( 'azurecurve-shortcodes-in-comments/azurecurve-shortcodes-in-comments.php' ) ) {
			echo "<a href='admin.php?page=azc-sic' class='azc_plugin_index'>Shortcodes in Comments</a>";
		}
		if ( is_plugin_active( 'azurecurve-shortcodes-in-widgets/azurecurve-shortcodes-in-widgets.php' ) ) {
			echo "<a href='admin.php?page=azc-siw' class='azc_plugin_index'>Shortcodes in Widgets</a>";
		}
		if ( is_plugin_active( 'azurecurve-tag-cloud/azurecurve-tag-cloud.php' ) ) {
			echo "<a href='admin.php?page=azc-tc' class='azc_plugin_index'>Tag Cloud</a>";
		}
		if ( is_plugin_active( 'azurecurve-taxonomy-index/azurecurve-taxonomy-index.php' ) ) {
			echo "<a href='admin.php?page=azc-ti' class='azc_plugin_index'>Taxonomy Index</a>";
		}
		if ( is_plugin_active( 'azurecurve-theme-switcher/azurecurve-theme-switcher.php' ) ) {
			echo "<a href='admin.php?page=azc-ts' class='azc_plugin_index'>Theme Switcher</a>";
		}
		if ( is_plugin_active( 'azurecurve-timelines/azurecurve-timelines.php' ) ) {
			echo "<a href='admin.php?page=azc-t' class='azc_plugin_index'>Timelines</a>";
		}
		if ( is_plugin_active( 'azurecurve-toggle-showhide/azurecurve-toggle-showhide.php' ) ) {
			echo "<a href='admin.php?page=azc-tsh' class='azc_plugin_index'>Toggle Show/Hide</a>";
		}
		echo "</span></div>";
		echo "<p style='clear: both' />";
		
		echo "<div style='display: block;'><h4>Other Available Plugins</h4>";
		echo "<span class='azc_plugin_index'>";
		if ( !is_plugin_active( 'azurecurve-bbcode/azurecurve-bbcode.php' ) ) {
			echo "<a href='https://wordpress.org/plugins/azurecurve-bbcode/' class='azc_plugin_index'>BBCode</a>";
		}
		if ( !is_plugin_active( 'azurecurve-comment-validator/azurecurve-comment-validator.php' ) ) {
			echo "<a href='https://wordpress.org/plugins/azurecurve-comment-validator/' class='azc_plugin_index'>Comment Validator</a>";
		}
		if ( !is_plugin_active( 'azurecurve-conditional-links/azurecurve-conditional-links.php' ) ) {
			echo "<a href='https://wordpress.org/plugins/azurecurve-conditional-links/' class='azc_plugin_index'>Conditional Links</a>";
		}
		if ( !is_plugin_active( 'azurecurve-display-after-post-content/azurecurve-display-after-post-content.php' ) ) {
			echo "<a href='https://wordpress.org/plugins/azurecurve-display-after-post-content/' class='azc_plugin_index'>Display After Post Content</a>";
		}
		if ( !is_plugin_active( 'azurecurve-filtered-categories/azurecurve-filtered-categories.php' ) ) {
			echo "<a href='https://wordpress.org/plugins/azurecurve-filtered-categories/' class='azc_plugin_index'>Filtered Categories</a>";
		}
		if ( !is_plugin_active( 'azurecurve-flags/azurecurve-flags.php' ) ) {
			echo "<a href='https://wordpress.org/plugins/azurecurve-flags/' class='azc_plugin_index'>Flags</a>";
		}
		if ( !is_plugin_active( 'azurecurve-floating-featured-image/azurecurve-floating-featured-image.php' ) ) {
			echo "<a href='https://wordpress.org/plugins/azurecurve-floating-featured-image/' class='azc_plugin_index'>Floating Featured Image</a>";
		}
		if ( !is_plugin_active( 'azurecurve-get-plugin-info/azurecurve-get-plugin-info.php' ) ) {
			echo "<a href='https://wordpress.org/plugins/azurecurve-get-plugin-info/' class='azc_plugin_index'>Get Plugin Info</a>";
		}
		if ( !is_plugin_active( 'azurecurve-insult-generator/azurecurve-insult-generator.php' ) ) {
			echo "<a href='https://wordpress.org/plugins/azurecurve-insult-generator/' class='azc_plugin_index'>Insult Generator</a>";
		}
		if ( !is_plugin_active( 'azurecurve-mobile-detection/azurecurve-mobile-detection.php' ) ) {
			echo "<a href='https://wordpress.org/plugins/azurecurve-mobile-detection/' class='azc_plugin_index'>Mobile Detection</a>";
		}
		if ( !is_plugin_active( 'azurecurve-multisite-favicon/azurecurve-multisite-favicon.php' ) ) {
			echo "<a href='https://wordpress.org/plugins/azurecurve-multisite-favicon/' class='azc_plugin_index'>Multisite Favicon</a>";
		}
		if ( !is_plugin_active( 'azurecurve-page-index/azurecurve-page-index.php' ) ) {
			echo "<a href='https://wordpress.org/plugins/azurecurve-page-index/' class='azc_plugin_index'>Page Index</a>";
		}
		if ( !is_plugin_active( 'azurecurve-posts-archive/azurecurve-posts-archive.php' ) ) {
			echo "<a href='https://wordpress.org/plugins/azurecurve-posts-archive/' class='azc_plugin_index'>Posts Archive</a>";
		}
		if ( !is_plugin_active( 'azurecurve-rss-feed/azurecurve-rss-feed.php' ) ) {
			echo "<a href='https://wordpress.org/plugins/azurecurve-rss-feed/' class='azc_plugin_index'>RSS Feed</a>";
		}
		if ( !is_plugin_active( 'azurecurve-rss-suffix/azurecurve-rss-suffix.php' ) ) {
			echo "<a href='https://wordpress.org/plugins/azurecurve-rss-suffix/' class='azc_plugin_index'>RSS Suffix</a>";
		}
		if ( !is_plugin_active( 'azurecurve-series-index/azurecurve-series-index.php' ) ) {
			echo "<a href='https://wordpress.org/plugins/azurecurve-series-index/' class='azc_plugin_index'>Series Index</a>";
		}
		if ( !is_plugin_active( 'azurecurve-shortcodes-in-comments/azurecurve-shortcodes-in-comments.php' ) ) {
			echo "<a href='https://wordpress.org/plugins/azurecurve-shortcodes-in-comments/' class='azc_plugin_index'>Shortcodes in Comments</a>";
		}
		if ( !is_plugin_active( 'azurecurve-shortcodes-in-widgets/azurecurve-shortcodes-in-widgets.php' ) ) {
			echo "<a href='https://wordpress.org/plugins/azurecurve-shortcodes-in-widgets/' class='azc_plugin_index'>Shortcodes in Widgets</a>";
		}
		if ( !is_plugin_active( 'azurecurve-tag-cloud/azurecurve-tag-cloud.php' ) ) {
			echo "<a href='https://wordpress.org/plugins/azurecurve-tag-cloud/' class='azc_plugin_index'>Tag Cloud</a>";
		}
		if ( !is_plugin_active( 'azurecurve-taxonomy-index/azurecurve-taxonomy-index.php' ) ) {
			echo "<a href='https://wordpress.org/plugins/azurecurve-taxonomy-index/' class='azc_plugin_index'>Taxonomy Index</a>";
		}
		if ( !is_plugin_active( 'azurecurve-theme-switcher/azurecurve-theme-switcher.php' ) ) {
			echo "<a href='https://wordpress.org/plugins/azurecurve-theme-switcher/' class='azc_plugin_index'>Theme Switcher</a>";
		}
		if ( !is_plugin_active( 'azurecurve-timelines/azurecurve-timelines.php' ) ) {
			echo "<a href='https://wordpress.org/plugins/azurecurve-timelines/' class='azc_plugin_index'>Timelines</a>";
		}
		if ( !is_plugin_active( 'azurecurve-toggle-showhide/azurecurve-toggle-showhide.php' ) ) {
			echo "<a href='https://wordpress.org/plugins/azurecurve-toggle-showhide/' class='azc_plugin_index'>Toggle Show/Hide</a>";
		}
		echo "</span></div>";
	}
}

?>