<?php                                    
class WP_Power_Tools_Admin extends WP_Power_Tools {
  
  private $_form_elements_input   = array();
  private $_form_elements_output  = array();
  
  function __construct(){
    parent::__construct();
    $this->_form_elements_input["general_options"][]=array( 
      'index'       =>    1, 
      'type'        =>    'checkbox', 
      'label'       =>    'Hide the Toolbar in Public Site ( for all logged in users )', 
      'id'          =>    'hide-bar', 
      'name'        =>    'hide-bar', 
      'optionName'  =>    'hide-bar'
    );
    
    $this->_form_elements_input["general_options"][]=array( 
      'index'       =>    2, 
      'type'        =>    'checkbox', 
      'label'       =>    'Hide the Toolbar in Administartion Area ( for all logged in users )', 
      'id'          =>    'hide-admin-bar', 
      'name'        =>    'hide-admin-bar', 
      'optionName'  =>    'hide-admin-bar'
    );

    $this->_form_elements_input["general_options"][]=array( 
      'index'       =>    3, 
      'type'        =>    'checkbox', 
      'label'       =>    'Enable Google Analytics Tracking', 
      'id'          =>    'google-tracking', 
      'name'        =>    'google-tracking', 
      'optionName'  =>    'google-tracking'
    );
    
    $this->_form_elements_input["general_options"][]=array( 
      'index'       =>    4, 
      'type'        =>    'text', 
      'label'       =>    'Google Analytics Profile ID (UA-XXXX-X)', 
      'id'          =>    'google-tracking-profile', 
      'name'        =>    'google-tracking-profile', 
      'optionName'  =>    'google-tracking-profile'
    );


        
    
    add_action( 'admin_menu',             array( &$this, 'WPPT_admin_menu' ) );
    add_action( 'admin_enqueue_scripts',  array( &$this, 'WPPT_admin_header_files' ) );
    }
  function WPPT_admin_header_files(){
    wp_register_style( 'WPPT_admin_css', plugin_dir_url( __FILE__ )."css/wppt-admin.css", false, '1.0.0'  );
    wp_register_script( 'WPPT_admin_js', plugin_dir_url( __FILE__ )."js/wppt-admin.js", array( "jquery" ), '1.0.0'  );
    wp_enqueue_style( 'WPPT_admin_css' );
    wp_enqueue_script( 'WPPT_admin_js' );
  }
  
  function WPPT_admin_menu() {
    add_menu_page( $this->plugin_name.' Options', $this->short_name, 'manage_options', $this->prefix."_general_options", array( &$this, 'general_options' ), $this->plugin_url."images/wppt_heading_icon.png" );
    add_submenu_page( $this->prefix."_general_options", $this->plugin_name.' Administration', "General Options", "manage_options", $this->prefix."_general_options", array( &$this, 'general_options' ) );
    add_submenu_page( $this->prefix."_general_options", $this->plugin_name.' Other Option', "Options Manager", "manage_options", $this->prefix."_wordpress_options", array( &$this, 'wordpress_options' ) );
  }
    
  function general_options(){
    $this->success_message  =   "";
    $this->error_message    =   "";
    $this->admin_screen     =   "general_options";
    $this->title            =   "WordPress Power Tools - General Options";
    $this->current_nonce    =   $this->prefix.$this->admin_screen."nonce";
    $this->success_message  =   trim( $this->success_message  );
    $this->error_message    =   trim( $this->error_message );
    if ( 'POST' == $_SERVER['REQUEST_METHOD'] && check_admin_referer( $this->current_nonce, '_wpnonce' ) ) {
      $this_options = get_option( $this->prefix."_options" );  
      foreach( $this->_form_elements_input[$this->admin_screen] as $element ) {
        switch( $element['type'] ) {
          case checkbox:
            if ( $_POST[$element['id']] == "on" ){
              $this_options[$element['id']] = 1;
            } else {
              $this_options[$element['id']] = 0;
            }
          break;
          case text:
            $this_options[$element['id']]=$_POST[$element['id']];
          break;          
          case textarea:
            $this_options[$element['id']]=$_POST[$element['id']];
          break;          
        }
      }
      update_option( $this->prefix."_options", $this_options );
      $this->success_message = "Successfully updated options!";      
    }
    $this_options = get_option( $this->prefix."_options" );  
    foreach( $this->_form_elements_input[$this->admin_screen] as $element ){
      switch( $element['type'] ) {
        case checkbox:
          $template = '<label for="%s">%s</label><input type="checkbox" name="%s" id="%s" %s><div class="clear-both"></div>';
          if ( $this_options[$element['optionName']] == 1 ){
            $checked = ' checked="checked" ';
          }
          else {
            $checked = '';
          }
          $this->_form_elements_output[$this->admin_screen][] = sprintf( $template, $element['id'], $element['label'], $element['name'], $element['id'], $checked );
        break;
        case text:
          $template = '<label for="%s">%s</label><input type="text" name="%s" id="%s" value="%s"><div class="clear-both"></div>';
          $this->_form_elements_output[$this->admin_screen][] = sprintf( $template, $element['id'], $element['label'], $element['name'], $element['id'], $this_options[$element['optionName']] );
        break;
        case textarea:
          $template = '<label for="%s">%s</label><textarea name="%s" id="%s">%s</textarea><div class="clear-both"></div>';
          $this->_form_elements_output[$this->admin_screen][] = sprintf( $template, $element['id'], $element['label'], $element['name'], $element['id'], $this_options[$element['optionName']] );
        break;        
      }
    }
    if ( $_GET['refresh'] == 1 ) {
      ?>
      <script type="text/javascript">
        function refresh_WP_general_options() {
          window.location = "<?php echo $this->get_raw_url()."?page=".$_GET['page']."&refresh=2"; ?>";
        }
        setTimeout( "refresh_WP_general_options()", 3000 );
      </script>      
      <?php
    $this->success_message = $this->success_message.'<br /><br />Your options will propogate automatically in 3 seconds. If this does not happen or if you do not want to wait <a href="'.$url.'">click here</a>.';
    }
    if ( $_GET['refresh'] == 2 ) {
      $this->success_message = $this->success_message.'<span class="bold">Successfully updated options!</span>';
    }

    ?>
    <div class="wrap">
      <?php $this->draw_heading();$this->draw_success_message();$this->draw_error_message(); ?>
      <form id="WPPT-admin" method="post" action="<?php echo $this->get_raw_url()."?page=".$_GET['page']."&refresh=1"; ?>">
        <?php
        foreach( $this->_form_elements_output[$this->admin_screen] as $element ){
          echo $element;
        }
        ?>
        <input type="hidden" name="biap_options_submit" value="true">
        <?php   echo wp_nonce_field( $this->current_nonce, "_wpnonce", true, true  ); ?>
        <div class="WPPT-notice"><h2>What's New?</h2>
        <ul>
          <li>Added <a href="http://analytics.google.com">Google Analytics</a> support.</li>
          <li>Added menu item linking to live site when hiding admin bar.</li>  
          <li>Added front-end interface to manage/delete options set in your options database table.</li>
            
        </ul>
        </div>
        <input class="button-primary" type="submit" value="Save" />
      </form>
    </div><?php
    }
  function wordpress_options(){
    $option_to_delete = "";
    global $wpdb;
    $this->wordpress_default_options        =   array( "siteurl", "blogname", "blogdescription", "users_can_register", "admin_email", "start_of_week", "use_balanceTags", "use_smilies", "require_name_email", "comments_notify", "posts_per_rss", "rss_excerpt_length", "rss_use_excerpt", "mailserver_url", "mailserver_login", "mailserver_pass", "mailserver_port", "default_category", "default_comment_status", "default_ping_status", "default_pingback_flag", "default_post_edit_rows", "posts_per_page", "what_to_show", "date_format", "time_format", "links_updated_date_format", "links_recently_updated_prepend", "links_recently_updated_append", "links_recently_updated_time", "comment_moderation", "moderation_notify", "permalink_structure", "gzipcompression", "hack_file", "blog_charset", "moderation_keys", "active_plugins", "home", "category_base", "ping_sites", "advanced_edit", "comment_max_links", "gmt_offset", "default_email_category", "recently_edited", "use_linksupdate", "template", "stylesheet", "comment_whitelist", "blacklist_keys", "comment_registration", "open_proxy_check", "rss_language", "html_type", "use_trackback", "default_role", "db_version", "wp_user_roles", "uploads_use_yearmonth_folders", "upload_path", "secret", "blog_public", "default_link_category", "show_on_front", "cron", "doing_cron", "sidebars_widgets", "widget_pages", "widget_calendar", "widget_archives", "widget_meta", "widget_categories", "widget_recent_entries", "widget_text", "widget_rss", "widget_recent_comments", "widget_wholinked", "widget_polls", "tag_base", "page_on_front", "page_for_posts", "page_uris", "page_attachment_uris", "show_avatars", "avatar_rating", "upload_url_path", "thumbnail_size_w", "thumbnail_size_h", "thumbnail_crop", "medium_size_w", "medium_size_h", "dashboard_widget_options", "current_theme", "auth_salt", "avatar_default", "enable_app", "enable_xmlrpc", "logged_in_salt", "recently_activated", "random_seed", "large_size_w", "large_size_h", "image_default_link_type", "image_default_size", "image_default_align", "close_comments_for_old_posts", "close_comments_days_old", "thread_comments", "thread_comments_depth", "page_comments", "comments_per_page", "default_comments_page", "comment_order", "use_ssl", "sticky_posts", "dismissed_update_core", "update_themes", "nonce_salt", "update_core", "uninstall_plugins", "wporg_popular_tags", "stats_options", "stats_cache", "rewrite_rules", "update_plugins", "category_children", "timezone_string", "can_compress_scripts", "db_upgraded", "_transient_doing_cron", "_transient_plugins_delete_result_1", "_transient_plugin_slugs", "_transient_random_seed", "_transient_rewrite_rules", "_transient_update_core", "_transient_update_plugins", "_transient_update_themes", "widget_search", "_site_transient_theme_roots", "_site_transient_timeout_theme_roots", "_site_transient_update_core", "_site_transient_update_plugins", "_site_transient_update_themes", "_transient_timeout_plugin_slugs", "default_post_format", "embed_autourls", "embed_size_h", "embed_size_w", "initial_db_version", "widget_links", "widget_nav_menu", "widget_recent-comments", "widget_recent-posts", "widget_tag_cloud" );
    $this->wordpress_options_fuzzy_matches  =   array( "_transient_" );
    $this->success_message                  =   "";
    $this->error_message                    =   "";
    $this->admin_screen                     =   "wordpress_options";
    $this->title                            =   "WordPress Power Tools - WP Options Manager";
    $this->current_nonce                    =   $this->prefix.$this->admin_screen."nonce";
    $this->success_message                  =   trim( $this->success_message );
    if ( 'GET' == $_SERVER['REQUEST_METHOD'] && strlen( $_GET['option_to_delete'] )>0 ) {
      check_admin_referer( 'delete_option_nonce' );
      $option_to_delete = urldecode( $_GET['option_to_delete'] );
      if ( get_option( $option_to_delete ) ) {
        if ( delete_option( $option_to_delete ) ) {
          $this->success_message = "Successfully deleted '$option_to_delete'";
        }
      } else {
        $this->error_message = "Problem deleting option, verify the option '$option_to_delete' exists and try again.";
      }
    }
    echo "<div class=\"wrap\">";
    $this->draw_heading();
    $this->draw_success_message();
    $this->draw_error_message(); 
    $optArray = get_alloptions();
    $output_template = '<tr %s ><td class="delete-opt">%s</td><td class="google-it"><a href="%s" target="_BLANK">?</a></td><td class="opt-name"><span class="the-opt-name">%s</span></td><td class="opt-val">%s</td></tr>';
    foreach ( $optArray as $opt_name => $opt_val ){
      $page = $_GET['page'];
      substr( $url, 0, strpos( $url, '?' ) );
      $opt_name_encode = urlencode( $opt_name );
      $delete_link = $this->get_raw_url()."?page=".$_GET['page']."&option_to_delete=".$opt_name_encode;
      $delete_link = wp_nonce_url( $delete_link, "delete_option_nonce" );
      if ( in_array( $opt_name, $this->wordpress_default_options ) ) {
        $class = ' class="default-options"';$delete_option_link = '';
      } else {
        $class = '';
        $delete_option_link = '[<a href="'.$delete_link.'" class="WPPT-delete-option">x</a>]';
      }
      foreach( $this->wordpress_options_fuzzy_matches as $matcher ){
        if ( strpos( $opt_name, $matcher ) !== false ){$class=' class="default-options"';}
        }  
      if ( is_serialized( $opt_val ) ){
        $opt_val = unserialize( $opt_val );
        ob_start();
        print_r( $opt_val  );
        $opt_val = ob_get_clean();
        $opt_val = htmlentities( $opt_val );
        $opt_val = "<PRE>$opt_val</PRE>";
      } else {
        $opt_val = htmlentities( $opt_val );
      }
      if ( strlen( trim( $opt_val ) )>0 && strlen( trim( $opt_name ) )>0 ) {
        if ( $opt_name != $option_to_delete ){$rows[] = str_replace( "  >", ">", sprintf( $output_template, $class, $delete_option_link, "http://www.google.com/search?q=".urlencode( '"wp_options" "'.$opt_name.'"' ), $opt_name, $opt_val ) );}
      }
      }
      ?>
      <div class="wrap">
      	<div id="WPPT-wordpress-options">
        	<div class="error">
            	<p><span class="bold">Warning</span> Carelessly deleting options can have drastic, irreversible consequences, <span class="bold">Be sure to back up your database before proceeding and </span> make sure you know know exactly what you are doing before deleting any options.</p>
	        </div>
          <div id="key">
            <h2>Key</h2>
            <table id="option-key">
              <tr>
                <td><p><span class="wordpress-option-span">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></p></td>
                <td><p>Background color signifies a Default WordPress Option. These cannot be deleted with WPPT, but can be hidden ( see below ).</p></td>
              </tr>
              <tr>
                <td id="question-mark-td"><p>?</p></td>
                <td><p>Links to a Google search to help identify the option's purpose.</p></td>
              </tr>
              <tr><td id="x-td"><p>[x]</p></td><td><p>Delete this option.</p></td></tr>
            </table>
          </div>
          <div class="clear-both"></div>
          <a href="#" class="button" id="hide-default-options">Hide Default WordPress Options</a>
          <a href="#" class="button" id="show-default-options">Show Default WordPress Options</a>
          <table>
            <tr id="WPPT-options-head">
              <td>Delete</td>
              <td>What's This?</td>
              <td>Option Name</td>
              <td>Option Value</td>
            </tr>
            <?php 
            foreach( $rows as $row  ) {
              echo "$row\n";
            } ?>
          </table>
        </div>
      </div><?php
  }      
  
  function draw_heading() {
    ?>
    <div class="icon32 icon32-posts-post" id="icon-wppt">
      <br>
    </div>
    <h2> <?php echo $this->title; ?></h2>
    <?php
  }
    
  function draw_success_message() {
    
    if ( strlen( trim( $this->success_message  )  ) > 0  ){
      ?>
      <div class="updated">
        <p class="bold"><?php echo $this->success_message; ?></p>
      </div>
      <?php
    }
  }
  
  function draw_error_message(){
    if ( strlen( trim( $this->error_message  )  )>0  ) {
      ?> 
      <div class="error">
        <p class="bold"><?php echo $this->error_message; ?></p>
      </div>
      <?php
    }
  }    
  
  function get_url() {
    $url = 'http';
    if ( $_SERVER['HTTPS'] == 'on'  ) {
      $url .= 's';
      }
    $url .= '://';
    $url .= $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    return $url;
    }
  
  function get_raw_url() {
    $url = $this->get_url();
    return substr( $url, 0, strpos( $url, '?'  )  );
  }  
}