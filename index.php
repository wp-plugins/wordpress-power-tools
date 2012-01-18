<?php
/*
Plugin Name: WordPress Power Tools
Plugin URI: http://matgargano.com/WPPT
Description: A suite of tools that takes care of non-sense dirty work on WordPress installs.
Version: 1.3
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

register_activation_hook(__FILE__, 'wppt_activate_plugin');
register_deactivation_hook(__FILE__, 'wppt_deactivate_plugin');
define('wpptMinWpVersion','3.2');
define('wpptMinPhpVersion','5.2');
function wppt_deactivate_plugin() {global $pluginPrefix;delete_option($pluginPrefix."_active");}
function wppt_activate_plugin() {global $wp_version;if ( version_compare( phpversion(), wpptMinPhpVersion, '<' ) || version_compare( $wp_version, wpptMinWpVersion, '<' ) ) {trigger_error('', E_USER_ERROR);}}
function _wppt_activation_message() {global $pluginPrefix;global $activationNotice;if(get_option($pluginPrefix."_active")<>1){if(trim($activationNotice)!=""){echo $activationNotice;}global $pluginPrefix;update_option($pluginPrefix."_active",1);}}

if ($_GET['action'] == 'error_scrape') {
  if(version_compare( phpversion(), wpptMinPhpVersion, '<' )) {$errors[]="PHP Version " . wpptMinPhpVersion." or higher";} 
  if(version_compare( $wp_version,wpptMinWpVersion, '<' )){$errors[]="WordPress ".wpptMinWpVersion." or higher";}
  $counter=0;
  foreach($errors as $error){
    if($counter>0){$errorOutput.=" and ";}
    $errorOutput.=$error;
    $counter++;
    }
  $errorOutput="This plugin requires ".$errorOutput.". Contact your hosting provider or system administrator and ask them to upload to the latest versions.";
  die($errorOutput);
  
  }
else {
	add_action('admin_notices', '_wppt_activation_message');
  include($pluginDirUrl."wppt.php");
  include($pluginDirUrl."wppt-admin.php");
  $wppt=new wpptAdmin;
	}
?>