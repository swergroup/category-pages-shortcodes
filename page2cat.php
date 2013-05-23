<?php
/*
Plugin Name: Page2Cat: Category Pages & Posts Shortcodes
Plugin URI: http://wordpress.org/extend/plugins/page2cat/
Description: Display posts/pages content (or lists of posts) with handy shortcodes, and map categories to pages directly in the admin area.
Version: 3.3.1
Author: SWERgroup
Author URI: http://swergroup.com/
License: GPL2
*/

/*  
    SomeRight 2012+  Paolo Tresso / SWERgroup  (email : plugins@swergroup.com)
    Rewrite of pixline's "Category Page" plugin, GPL2 (2007)
    Plugin based on Empty Plugin Template 0.1.1.2 (http://1manfactory.com/ept)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
?><?php

define( 'SWER_PUGIN_NAME', 'Category Pages & Posts Shortcodes' );
define( 'SWER_PLUGIN_DIRECTORY', 'page2cat' );
define( 'SWER_CURRENT_VERSION', '3.3.1' );
define( 'SWER_I18N_DOMAIN', 'page2cat' );
#define( 'SWER_LOGPATH', str_replace( '\\', '/', WP_CONTENT_DIR ).'/swer-logs/' );

define( 'P2C_PATH', plugin_dir_path( __FILE__ ) );
define( 'P2C_URL', plugins_url( 'page2cat' ) );


include( P2C_PATH . '/lib/core.class.php' );
include( P2C_PATH . '/lib/admin.class.php' );
include( P2C_PATH . '/lib/shortcodes.class.php' );


// load language files
function page2cat_set_lang_file() {
	# set the language file
	$currentLocale = get_locale();
	if ( !empty( $currentLocale ) ) {
		$moFile = dirname( __FILE__ ) . '/lang/' . $currentLocale . '.mo';
		if ( @file_exists( $moFile ) && is_readable( $moFile ) ) {
			load_textdomain( SWER_I18N_DOMAIN, $moFile );
		}
	}
}
page2cat_set_lang_file();


register_activation_hook( __FILE__, 'page2cat_activate' );
register_deactivation_hook( __FILE__, 'page2cat_deactivate' );
register_uninstall_hook( __FILE__, 'page2cat_uninstall' );

// activating the default values
function page2cat_activate() {
 global $wp_version;
 if ( version_compare( $wp_version, '3.5', '<=' ) ) {
     deactivate_plugins( __FILE__ );
     wp_die( __( 'Page2cat requires WordPress 3.5 or newer (yours: '.$wp_version.')', 'page2cat' ), __( 'Please upgrade your Wordpress.', 'page2cat' ) );
 }

 delete_option( 'pixline_page2cat_version' );
 delete_option( 'p2c_use_empty' );
 delete_option( 'p2c_show_used_pages' );
 delete_option( 'p2c_catlist_limit' );
 delete_option( 'p2c_catlist_title' );
 delete_option( 'p2c_excerpt_settings' );
 delete_option( 'p2c_post_settings' );
 delete_option( 'p2c_excerpt_length' );
 delete_option( 'p2c_use_thumbnail' );
 delete_option( 'p2c_img_class' );
 delete_option( 'p2c_title_class' );
 delete_option( 'p2c_thumbnail_size' );
 delete_option( 'p2c_use_img' );

}

function page2cat_deactivate() {
	delete_option( 'page2cat_options' );
}

// uninstalling
function page2cat_uninstall() {
	delete_option( 'page2cat_options' );
	if (function_exists( 'page2cat_deleteLogFolder' ) ) page2cat_deleteLogFolder();
}

