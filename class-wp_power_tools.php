<?php
class WP_Power_Tools {
  var $short_name;
  var $plugin_prefix;     
  function __construct(){   
    $this->plugin_name                =   "WordPress Power Tools";
    $this->short_name                 =   "WP Power Tools";
    $this->prefix                     =   "WPPT";
    $this->admin_base                 =   get_admin_url();
    $this->plugin_settings_url        =   'admin.php?page='.$this->prefix;
    $this->plugin_full_settings_url   =   $this->adminBase.$this->plugin_settings_url;
    $this->plugin_url                 =   plugin_dir_url(__FILE__);
    $this->options                    =   get_option($this->prefix."_options");

    
    
    if ( $this->options [ 'google-tracking' ] ==  1){
       add_action('wp_head', array(&$this,'WPPT_add_google_analytics'));
    }
    
    


    if ( $this->options [ 'hide-bar' ] ==  1){
      add_filter( 'show_admin_bar' ,  array(&$this,'remove_that_darn_bar'));
    }
    
    if($this->options [ 'hide-admin-bar' ] == 1 ) {
      add_action('admin_menu', array(&$this, 'WPPT_create_home_menu_item'));
      add_filter('admin_head',array(&$this, 'remove_that_darn_bar_admin'));
      add_filter('admin_head',array(&$this, 'remove_that_darn_bar_admin_extra_css'));
      add_action('after_setup_theme', array(&$this, 'WPPT_redirect_from_admin_menu'));
      $refresh_url= $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
    }
  }

  function remove_that_darn_bar_admin() { 
    remove_action( 'admin_footer', 'wp_admin_bar_render', 1000 );
  } 
  
  function remove_that_darn_bar_admin_extra_css() {
    echo '<style>
            body.admin-bar #wpcontent, body.admin-bar #adminmenu { padding-top: 0px !important; }
          </style>'; 
  }  
  
  function remove_that_darn_bar() { 
    return false;
  }
  
  function WPPT_create_home_menu_item() {
    $WPPT_options=get_option($this->prefix."_options");
    if( ! is_numeric ( $this->options [ 'menu-location' ] ) ) {
      foreach ( $GLOBALS [ 'menu' ] as $key => $val ) {
    		$keys[] = $key;
    	}
    	$min_key = min ( $keys );
    	if ( $min_key > 1 ) {
        $key = 1;
      }
    	else {
    		$counter = 1;
    		$found = 0;	
    		do{
    			$counter++;	
    			if(!in_array($counter, $keys)){
            $found = 1;
            $key = $counter;
          }
    		} while ( $found == 0 );
    	}		
    	$this->options[ 'menu-location' ]=$key;	
    	update_option( $this->prefix."_options",$this->options);
    }
      else {$key=$this->options[ 'menu-location' ];}
      	$name=get_bloginfo("name");
    	if(strlen($name)<1){$name="View Site";}
      add_menu_page('View Site',$name,'read', 'admin.php?goto=view_page',"","",$key);    
      }
  
  function WPPT_redirect_from_admin_menu($value) {
    global $pagenow;
    if ( $pagenow == 'admin.php' && ! empty ( $_GET[ 'goto' ] ) ) {
      switch ($_GET[ 'goto' ]) {
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
  
  function WPPT_add_google_analytics() {
  $WPPT_options=get_option($this->prefix."_options");
  ?>
  <script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', '<?php echo $WPPT_options['google-tracking-profile']; ?>']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

  </script>
  <?php
  }


}