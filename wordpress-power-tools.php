<?php
/*
Plugin Name: WordPress Power Tools
Plugin URI: http://matgargano.com/wordpress-power-tools/
Description: A suite of tools that takes care of non-sense dirty work on WordPress installs.
Version: 1.3.2
Author: Mat Gargano
Author URI: http://matgargano.com
Graphics retrieved from iconfinder.com ; 
  WordPress PowerTools Admin Menu graphic (Yusuke Kamiyamane, http://p.yusukekamiyamane.com/)
  WordPress PowerTools Admin Page graphic (Everaldo Coelho, http://www.everaldo.com/)  


License: Copyright 2011  Matthew Gargano  (email : mgargano@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA.
    
*/

register_activation_hook(__FILE__, 'WPPT_activate_plugin');
register_deactivation_hook(__FILE__, 'WPPT_deactivate_plugin');
define('WPPT_MIN_WP_VERSION','3.2');
define('WPPT_MIN_PHP_VERSION','5.2');
$plugin_prefix="WPPT";
function WPPT_deactivate_plugin() {
  global $plugin_prefix;
  delete_option($plugin_prefix."_active");
  delete_option($plugin_prefix."_options");
}
  
function WPPT_activate_plugin() {
  global $plugin_prefix;
  delete_option($plugin_prefix."_active");
  delete_option($plugin_prefix."_options");

  $wp_version = get_bloginfo('version');
  if ( version_compare( phpversion(), WPPT_MIN_PHP_VERSION, '<' ) ) {
    trigger_error('', E_USER_ERROR);
  }
  if ( version_compare( $wp_version, WPPT_MIN_WP_VERSION, '<' ) ){
    trigger_error('', E_USER_ERROR);
  }
}
 
function _WPPT_activation_message() {
  global $plugin_prefix;
  global $activation_notice;
  $wp_version = get_bloginfo('version');
  
  if ( get_option ( $plugin_prefix."_active" ) <> 1 ) {
    if ( trim ( $activation_notice ) != "" ) {
      echo $activation_notice;
    }
    update_option($plugin_prefix."_active",1);
  }
}
    
if ( $_GET['action'] == 'error_scrape' ) {
  if ( version_compare ( phpversion(), WPPT_MIN_PHP_VERSION, '<' ) ) {
    $errors[] = "PHP Version " . WPPT_MIN_PHP_VERSION." or higher, you are using ".phpversion();
  } 
  if ( version_compare ( get_bloginfo('version'), WPPT_MIN_WP_VERSION, '<' ) ){
    $errors[] = "WordPress ".WPPT_MIN_WP_VERSION." or higher, you are using ".get_bloginfo('version');
  }
  $counter = 0;
  if ( count ( $errors ) > 0 ) {
    foreach ( $errors as $error ) {
      if( $counter > 0 ) {
        $error_output .= " and ";
      }
      $error_output .= $error;
      $counter++;
    }
    $error_output = "This plugin requires ".$error_output.". Contact your hosting provider or system administrator and ask them to upload to the latest versions.";
    die($error_output);
  }
 } else {
  add_action('admin_notices', '_WPPT_activation_message');
  require_once("class-wp_power_tools.php");
  require_once("class-wp_power_tools_admin.php");
  $WP_power_tools_admin=new WP_Power_Tools_Admin;
}

  
?>