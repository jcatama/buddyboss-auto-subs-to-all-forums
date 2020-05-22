<?php
/*
Plugin Name: BuddyBoss Auto subscribe to All forums
Plugin URI:  https://github.com/jcatama/
Description: Auto subscribe to All forums after user registration. BuddyBoss Platform should be installed and active to use this plugin.
Version: 1.0.0
Author: John Albert Catama
Author URI: https://github.com/jcatama
License: Use this how you like!
*/

register_activation_hook( __FILE__, 'bb_auto_subs_to_all_forums_activate' );
function bb_auto_subs_to_all_forums_activate() {
  $plugin = plugin_basename( __FILE__ );
  if(!is_plugin_active('buddyboss-platform/bp-loader.php') and current_user_can('activate_plugins')) {
    wp_die('Sorry, but this plugin requires the BuddyBoss Platform Plugin to be installed and active. <br><a href="' . admin_url( 'plugins.php' ) . '">&laquo; Return to Plugins</a>');
    deactivate_plugins($plugin);
  }
}

add_action('user_register','bb_auto_subs_to_all_forums_after_registraion');
function bb_auto_subs_to_all_forums_after_registraion($user_id) {
  $all_forums = get_pages(
    [
      'post_type' => bbp_get_forum_post_type(),
      'numberposts' => -1,
      'post_status' => ['publish']
    ]
  );
  foreach($all_forums as $forums) {
    if(is_numeric($forums->ID)) {
      bbp_add_user_forum_subscription($user_id, $forums->ID);
    }
  }
}