<?php
class wpptAdmin extends wppt {
  private $_formElementsInput=array();
  private $_formElementsOutput=array();
  function __construct(){
    parent::__construct();
    $this->_formElementsInput["toolbarOptions"][]=array('index'=>1,'type'=>'checkbox','label'=>'Hide the Toolbar in Public Site (for all logged in users)','id'=>'hideBar','name'=>'hideBar','optionName'=>'hideBar');
    $this->_formElementsInput["toolbarOptions"][]=array('index'=>2,'type'=>'checkbox','label'=>'Hide the Toolbar in Administartion Area (for all logged in users)','id'=>'hideAdminBar','name'=>'hideAdminBar','optionName'=>'hideAdminBar');
    add_action('admin_menu', array(&$this, 'wpptAdminMenu'));
    add_action('admin_head', array(&$this, 'wpptAdminHeaderFiles'));
    }
  function wpptAdminHeaderFiles(){
    wp_register_style( 'wpptAdminCss', plugin_dir_url(__FILE__)."css/wppt-admin.css", false, '1.0.0' );
    wp_register_script( 'wpptAdminJs', plugin_dir_url(__FILE__)."js/wppt-admin.js", array("jquery"), '1.0.0' );
    wp_enqueue_style('wpptAdminCss');
    wp_enqueue_script('wpptAdminJs');
    }
  
  function wpptAdminMenu() {
    add_menu_page($this->pluginName.' Options', $this->shortName, 'manage_options', $this->pluginPrefix."_general_options", array(&$this, 'toolbarOptions'), $this->pluginUrl."images/wppt_heading_icon.png");
    add_submenu_page($this->pluginPrefix."_general_options",$this->pluginName.' Administration',"General Options","manage_options",$this->pluginPrefix."_general_options",array(&$this, 'toolbarOptions'));
    add_submenu_page($this->pluginPrefix."_general_options",$this->pluginName.' Other Option',"Options Manager","manage_options",$this->pluginPrefix."_wordpress_options",array(&$this, 'wordpressOptions'));
    }
    
  function toolbarOptions(){
    $this->successMessage="";
    $this->errorMessage="";
    $this->adminScreen="toolbarOptions";
    $this->title="WordPress Power Tools - General Options";
    $this->currentNonce=$this->pluginPrefix.$this->adminScreen."nonce";
    $this->successMessage=trim($this->successMessage);
    $this->errorMessage=trim($this->errorMessage);
      if('POST' == $_SERVER['REQUEST_METHOD'] && check_admin_referer($this->currentNonce,'_wpnonce')) {
      $thisOptions=get_option($this->prefix."_options");  
      foreach($this->_formElementsInput[$this->adminScreen] as $element){
        switch($element['type']){
          case checkbox:
            if($_POST[$element['id']]=="on"){$thisOptions[$element['id']]=1;} else {$thisOptions[$element['id']]=0;}
          break;
          }
        }
      update_option($this->prefix."_options",$thisOptions);
      $this->successMessage="Successfully updated options!";      
      }
    $thisOptions=get_option($this->prefix."_options");  
    foreach($this->_formElementsInput[$this->adminScreen] as $element){
      switch($element['type']){
        case checkbox:
          $template='<label for="%s">%s</label><input type="checkbox" name="%s" id="%s" %s><div class="clearboth"></div>';
          if($thisOptions[$element['optionName']]==1){
            $checked=' checked="checked" ';
            }
          else {$checked='';}
          $this->_formElementsOutput[$this->adminScreen][]=sprintf($template,$element['id'],$element['label'],$element['name'],$element['id'],$checked);
          break;
      }
    }
    if($_GET['refresh']==1){
      ?>
      <script type="text/javascript">
      function refreshWpptToolbarOptions() {
        window.location = "<?php echo $this->getRawUrl()."?page=".$_GET['page']."&refresh=2"; ?>";
        }
      setTimeout("refreshWpptToolbarOptions()", 3000);
      <?php
      $this->successMessage=$this->successMessage.'<p>Your options will propogate automatically in 3 seconds. If this does not happen or if you do not want to wait <a href="'.$url.'">click here</a>.';
      ?>
      </script>      
      <?php
      }
    if($_GET['refresh']==2){
        $this->successMessage=$this->successMessage.'<p class="bold">Successfully updated options!</p>';
      }

    ?>
    <div class="wrap"><?php $this->drawHeading();$this->drawSuccessMessage();$this->drawErrorMessage(); ?>
      <form id="wpptAdmin" method="post" action="<?php echo $this->getRawUrl()."?page=".$_GET['page']."&refresh=1"; ?>">
        <?php
        foreach($this->_formElementsOutput[$this->adminScreen] as $element){echo $element;}
        ?>
        <input type="hidden" name="biap_options_submit" value="true">
        <?php   echo wp_nonce_field( $this->currentNonce, "_wpnonce", true, true ); ?>
        <div class="wpptNotice"><h2>What's New?</h2>
        <ul>
          <li>Added menu item linking to live site when hiding admin bar.</li>  
          <li>Added front-end interface to manage/delete options set in your options database table.</li>
          <li>Completely rewritten object oriented and optimized code.</li>  
        </ul>
        </div>
        <input class="button-primary" type="submit" value="Save" />
      </form>
    </div><?
    }
  function wordpressOptions(){
    $optionToDelete="";
    $this->wordpressDefaultOptions=array("siteurl","blogname","blogdescription","users_can_register","admin_email","start_of_week","use_balanceTags","use_smilies","require_name_email","comments_notify","posts_per_rss","rss_excerpt_length","rss_use_excerpt","mailserver_url","mailserver_login","mailserver_pass","mailserver_port","default_category","default_comment_status","default_ping_status","default_pingback_flag","default_post_edit_rows","posts_per_page","what_to_show","date_format","time_format","links_updated_date_format","links_recently_updated_prepend","links_recently_updated_append","links_recently_updated_time","comment_moderation","moderation_notify","permalink_structure","gzipcompression","hack_file","blog_charset","moderation_keys","active_plugins","home","category_base","ping_sites","advanced_edit","comment_max_links","gmt_offset","default_email_category","recently_edited","use_linksupdate","template","stylesheet","comment_whitelist","blacklist_keys","comment_registration","open_proxy_check","rss_language","html_type","use_trackback","default_role","db_version","wp_user_roles","uploads_use_yearmonth_folders","upload_path","secret","blog_public","default_link_category","show_on_front","cron","doing_cron","sidebars_widgets","widget_pages","widget_calendar","widget_archives","widget_meta","widget_categories","widget_recent_entries","widget_text","widget_rss","widget_recent_comments","widget_wholinked","widget_polls","tag_base","page_on_front","page_for_posts","page_uris","page_attachment_uris","show_avatars","avatar_rating","upload_url_path","thumbnail_size_w","thumbnail_size_h","thumbnail_crop","medium_size_w","medium_size_h","dashboard_widget_options","current_theme","auth_salt","avatar_default","enable_app","enable_xmlrpc","logged_in_salt","recently_activated","random_seed","large_size_w","large_size_h","image_default_link_type","image_default_size","image_default_align","close_comments_for_old_posts","close_comments_days_old","thread_comments","thread_comments_depth","page_comments","comments_per_page","default_comments_page","comment_order","use_ssl","sticky_posts","dismissed_update_core","update_themes","nonce_salt","update_core","uninstall_plugins","wporg_popular_tags","stats_options","stats_cache","rewrite_rules","update_plugins","category_children","timezone_string","can_compress_scripts","db_upgraded","_transient_doing_cron","_transient_plugins_delete_result_1","_transient_plugin_slugs","_transient_random_seed","_transient_rewrite_rules","_transient_update_core","_transient_update_plugins","_transient_update_themes","widget_search","_site_transient_theme_roots","_site_transient_timeout_theme_roots","_site_transient_update_core","_site_transient_update_plugins","_site_transient_update_themes","_transient_timeout_plugin_slugs","default_post_format","embed_autourls","embed_size_h","embed_size_w","initial_db_version","widget_links","widget_nav_menu","widget_recent-comments","widget_recent-posts","widget_tag_cloud");
    $this->wordpressOptionsFuzzyMatches=array("_transient_");
    $this->successMessage="";
    $this->errorMessage="";
    $this->adminScreen="wordpressOptions";
    $this->title="WordPress Power Tools - WP Options Manager";
    $this->currentNonce=$this->pluginPrefix.$this->adminScreen."nonce";
    $this->successMessage=trim($this->successMessage);
        if('GET' == $_SERVER['REQUEST_METHOD'] && strlen($_GET['optionToDelete'])>0) {
          check_admin_referer('deleteOptionNonce');
          $optionToDelete=urldecode($_GET['optionToDelete']);
          if(get_option($optionToDelete)){
            if(delete_option($optionToDelete)){
              $this->successMessage="Successfully deleted '$optionToDelete'";
              }
            }
          else {$this->errorMessage="Problem deleting option, verify the option '$optionToDelete' exists and try again.";}
          }


?>
    <div class="wrap"><?php $this->drawHeading();$this->drawSuccessMessage();$this->drawErrorMessage(); ?>
    <?php
    global $wpdb;
    $optArray=get_alloptions();
    $outputTemplate='<tr %s ><td class="deleteOpt">%s</td><td class="googleIt"><a href="%s" target="_BLANK">?</a></td><td class="optName"><span class="theOptName">%s</span></td><td class="optVal">%s</td></tr>';
    foreach ($optArray as $optName=>$optVal){
      $page=$_GET['page'];
      substr($url, 0, strpos($url, '?'));
      $optNameEncode=urlencode($optName);
      $deleteLink=$this->getRawUrl()."?page=".$_GET['page']."&optionToDelete=".$optNameEncode;
      $deleteLink=wp_nonce_url($deleteLink,"deleteOptionNonce");
      if(in_array($optName,$this->wordpressDefaultOptions)){$class=' class="defaultOptions"';$deleteOptionLink='';} else {$class='';$deleteOptionLink='[<a href="'.$deleteLink.'" class="wppt_deleteOption">x</a>]';}
      foreach($this->wordpressOptionsFuzzyMatches as $matcher){
        if(strpos($optName,$matcher)!==false){$class=' class="defaultOptions"';}
        }  
      if(is_serialized($optVal)){
        $optVal=unserialize($optVal);
        ob_start();
        print_r( $optVal );
        $optVal=ob_get_clean();
        $optVal=htmlentities($optVal);
        $optVal="<PRE>$optVal</PRE>";
        } else {$optVal=htmlentities($optVal);}
      if(strlen(trim($optVal))>0 && strlen(trim($optName))>0){
        if($optName!=$optionToDelete){$rows[]=str_replace("  >",">",sprintf($outputTemplate,$class, $deleteOptionLink,"http://www.google.com/search?q=".urlencode('"wp_options" "'.$optName.'"'),$optName,$optVal));}
        }
      }
      echo '<div id="wpptWordPressOptions">
      <div class="error"><p><span class="bold">Warning</span> Carelessly deleting options can have drastic, irreversible consequences, make sure you are completely sure you know what you are doing before deleting any options.</p></div>
          <div id="key">
            <h2>Key</h2>
              <table id="optionKey">
                <tr><td><p><span class="wordpressOptionSpan">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></p></td><td><p>Background color signifies a Default WordPress Option. These cannot be deleted with WPPT, but can be hidden (see below).</p></td></tr>
                <tr><td id="questionMarkTd"><p>?</p></td><td><p>Links to a Google search to help identify the option\'s purpose.</p></td></tr>
                <tr><td id="xTd"><p>[x]</p></td><td><p>Delete this option.</p></td></tr>
            </table>
            </div>
          <div class="clearBoth"></div>
        <a href="#" class="button" id="hideDefaultOptions">Hide Default WordPress Options</a>
        <a href="#" class="button" id="showDefaultOptions">Show Default WordPress Options</a>
        <table>
        <tr id="wpptOptionsHead"><td>Delete</td><td>What\'s This?</td><td>Option Name</td><td>Option Value</td></tr>';
      foreach($rows as $row){
        echo "$row\n";
        }
      echo "</table></div>";
    ?>
    </div>
    <?php
    }      
  function drawHeading(){
    echo '<div class="icon32 icon32-posts-post" id="icon-wppt"><br></div><h2>'.$this->title.'</h2>';
    }
  function drawSuccessMessage(){
    if(strlen(trim($this->successMessage))>0){echo '<div class="updated"><p class="bold">'.$this->successMessage.'</p></div>';}
    }
  function drawErrorMessage(){
    if(strlen(trim($this->errorMessage))>0){echo '<div class="error"><p class="bold">'.$this->errorMessage.'</p></div>';}
    }    
  function getUrl() {
    $url = 'http';
    if ($_SERVER['HTTPS'] == 'on') {
      $url .= 's';
      }
    $url .= '://';
    $url .= $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    return $url;
    }
  function getRawUrl() {
    $url = $this->getUrl();
    return substr($url, 0, strpos($url, '?'));
    }  
  
  function buildGoogleUrl($query){
    $query=urlencode($query);
    $googleTemplate="http://www.google.com/search?q=%n";
    return sprintf($googleTemplate,$query);
    }
  
  }