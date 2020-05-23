<?php
/*
Plugin Name: BuddyBoss auto subscribe to all forums
Plugin URI:  https://wordpress.org/plugins/buddyboss-auto-subscribe-to-all-forums/
Description: Auto subscribe to all forums after user registration
Version:     1.0.0
Author:      John Albert Catama
Author URI:  https://github.com/jcatama
License:     GPL2
 
BuddyBoss auto subscribe to all forums is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.
 
BuddyBoss auto subscribe to all forums is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
 
You should have received a copy of the GNU General Public License
along with BuddyBoss auto subscribe to all forums. If not, see https://github.com/jcatama/buddyboss-auto-subs-to-all-forums/blob/master/LICENSE.md.
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