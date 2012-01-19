<?php
class wppt {
  var $shortName;
  var $pluginPrefix;     

 function __construct(){   
    $this->pluginName="WordPress Power Tools";
    $this->shortName="WP Power Tools";
    $this->pluginPrefix="wppt";
    $this->adminBase=get_admin_url();
    $this->pluginSettingsUrl='admin.php?page='.$this->pluginPrefix;
    $this->pluginFullSettingsUrl=$this->adminBase.$this->pluginSettingsUrl;
    $this->pluginUrl=plugin_dir_url(__FILE__);
    $this->options=get_option($this->prefix."_options");
    if($this->options['hideBar']==1){
      add_filter( 'show_admin_bar' ,  array(&$this,'remove_that_darn_bar'));
      }
    if($this->options['hideAdminBar']==1){
      add_action('admin_menu', array(&$this, 'wppt_create_home_menu_item'));
      add_filter('admin_head',array(&$this, 'remove_that_darn_bar_admin'));
      add_filter('admin_head',array(&$this, 'remove_that_darn_bar_admin_extra_css'));
      add_action('after_setup_theme', array(&$this, 'wppt_redirect_from_admin_menu'));
      $refresh_url= $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
      }
    }
  function remove_that_darn_bar_admin(){remove_action( 'admin_footer', 'wp_admin_bar_render', 1000 );} 
  function remove_that_darn_bar_admin_extra_css() {echo '<style>body.admin-bar #wpcontent, body.admin-bar #adminmenu { padding-top: 0px !important; }</style>'; }  
  function remove_that_darn_bar(){return false;}
  function wppt_create_home_menu_item() {
    $wpptOptions=get_option("wppt_options");
    if(!is_numeric($this->options['menuLocation'])){
    foreach($GLOBALS['menu'] as $key=>$val){
    		$keys[]=$key;
    	}
    	$minKey=min($keys);
    	if ($minKey>1){$key=1;}
    	else{
    		$counter=1;
    		$found=0;	
    		do{
    			$counter++;	
    			if(!in_array($counter,$keys)){$found=1;$key=$counter;}
    			} while ($found==0);
    		}		
    	$this->options['menuLocation']=$key;	
    	update_option($this->prefix."_options",$this->options);
      }
      else {$key=$this->options['menuLocation'];}
      	$name=get_bloginfo("name");
    	if(strlen($name)<1){$name="View Site";}
      add_menu_page('View Site',$name,'read', 'admin.php?goto=view_page',"","",$key);    
      }
  
  function wppt_redirect_from_admin_menu($value) {
    global $pagenow;
    if ($pagenow=='admin.php' && !empty($_GET['goto'])) {
      switch ($_GET['goto']) {
        case 'view_page':
          wp_redirect(site_url());
          break;
        default:
          wp_safe_redirect('/wp-admin/');
          break;
        }
      exit;
      }
    }
  }