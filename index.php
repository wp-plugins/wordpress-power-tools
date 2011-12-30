<?php/*Plugin Name: WordPress PowerToolsPlugin URI: http://matgargano.com/WPPTDescription: OK, I admit, there's not much power to these tools, but I wanted to get the ball rolling on a solution that handles the tedious things that I perform every time I install WordPress.Version: 0.3Author: Mat GarganoAuthor URI: http://matgargano.comGraphics retrieved from iconfinder.com ;   WordPress PowerTools Admin Menu graphic (Yusuke Kamiyamane, http://p.yusukekamiyamane.com/)  WordPress PowerTools Admin Page graphic (Everaldo Coelho, http://www.everaldo.com/)  License: Copyright 2011  Matthew Gargano  (email : mgargano@gmail.com)    This program is free software; you can redistribute it and/or modify    it under the terms of the GNU General Public License, version 2, as     published by the Free Software Foundation.    This program is distributed in the hope that it will be useful,    but WITHOUT ANY WARRANTY; without even the implied warranty of    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the    GNU General Public License for more details.    You should have received a copy of the GNU General Public License    along with this program; if not, write to the Free Software    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA..*/


add_action('admin_menu', 'wppt_admin_menu'); 
add_action('admin_enqueue_scripts', 'load_wppt_admin');

function wppt_admin_menu() {
  add_menu_page('WP Powertools Options', 'WP Powertools', 'administrator', "wppt_admin_page", "wppt_admin", plugin_dir_url(__FILE__)."/images/wpptIcon.png");
  }

function wppt_admin() {
  if (!current_user_can('manage_options'))  {
    wp_die( __('You do not have sufficient permissions to access this page.') );
    }
  if($_POST['biap_options_submit']=="true" && check_admin_referer('wppt_options_nonce','_wpnonce')) {
  if($_POST['hideBar']=="on") {$hideBar=1;} else {$hideBar="no";}
  if($_POST['hideBarAdmin']=="on") {$hideBarAdmin=1;} else {$hideBarAdmin="no";}
    $wpptOptionsArray=array(
      'hideBar'=>$hideBar,
      'hideBarAdmin'=>$hideBarAdmin
      );
      
    update_option("wppt_options",serialize($wpptOptionsArray));
    $outputMessage="Successfully updated options!";    
  
  }

$wpptOptions=get_option("wppt_options");
if (!is_array($wpptOptions)){$wpptOptionsArray=unserialize($wpptOptions);}

if ($wpptOptionsArray['hideBar']==1) {$hideBar=1;} else {}
if ($wpptOptionsArray['hideBarAdmin']==1) {$hideBarAdmin=1;if($outputMessage<>""){$outputMessage.="<br /><br /><span class=\"bold\">Note: You may need to reload to see the bar in the back end</span>";}} else {}

  ?>                                                                                                                                   
  <div class="wrap"><div class="icon32 icon32-posts-post" id="icon-wppt"><br></div><h2>WordPress Powertools - Options Panel </h2></div>
  
  <?php if (strlen($outputMessage)>0){echo '<div class="updated wpptPadNoLeft"><p class="bold">'.$outputMessage.'</p></div>';} ?>
  <form id="wpptAdmin" method="post" action="">
  <label for="hideBar">Hide Administration Bar in Public Site (for all logged in users)</label><input type="checkbox" name="hideBar" id="hideBar" <?php if ($hideBar==1){echo 'checked="checked"';} ?>><div class="clearboth"></div>
    <label for="hideBarAdmin">Hide Administration Bar in Administartion Area (for all logged in users)</label><input type="checkbox" name="hideBarAdmin" id="hideBarAdmin" <?php if ($hideBarAdmin==1){echo 'checked="checked"';} ?>><div class="clearboth"></div>  
  <input type="hidden" name="biap_options_submit" value="true">
  <?php   echo wp_nonce_field( "wppt_options_nonce", "_wpnonce", true, true ); ?>
 
 
 
  <div class="wpptNotice"><h2>Powertools??!! It's just ONE option!</h2>
  <p>I know, I know this is far from a "power tool" - but remember every single journey starts with a single step, and this is my first foray into developing a WordPress plugin for the WordPress directory. Stay tuned, I'll update this soon with (more) useful options.</p>
  </div>
  <input class="button-primary" type="submit" value="Save" />
  </form>
  
  
  
  <?php
  }
  
function load_wppt_admin() {
  wp_register_style( 'wppt_admin_css', plugin_dir_url(__FILE__)."css/wppt-admin.css", false, '1.0.0' );
  wp_enqueue_style( 'wppt_admin_css' );
  }



  function remove_that_darn_bar_admin(){remove_action( 'admin_footer', 'wp_admin_bar_render', 1000 );} 
  function remove_that_darn_bar_admin_extra_css() {echo '<style>body.admin-bar #wpcontent, body.admin-bar #adminmenu { padding-top: 0px !important; }</style>'; }  

  function remove_that_darn_bar(){return false;}


$wpptOptions=get_option("wppt_options");
if (!is_array($wpptOptions)){$wpptOptionsArray=unserialize($wpptOptions);}
if ($wpptOptionsArray['hideBar']==1) {
  add_filter( 'show_admin_bar' , 'remove_that_darn_bar');
  }
if ($wpptOptionsArray['hideBarAdmin']==1) {
 add_filter('admin_head','remove_that_darn_bar_admin');
 add_filter('admin_head','remove_that_darn_bar_admin_extra_css');
 $refresh_url= $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];

} 



// Below are pieces that will be used at a later date
//
// register_activation_hook( __FILE__, 'wppt_activate' );
// function wppt_activate(){}



?>