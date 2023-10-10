<?php
/*
Plugin Name: CB Unique Event Card Generator
Description: Custom plugin to generate event cards.
Version: 1.0
Author: Cyrus Baybay
*/

// Registering Custom Post Type for Events
function cb_register_event_post_type() {
    $args = array(
        'public' => true,
        'label'  => 'Event Cards',
        'supports' => array('title', 'editor'),
        'menu_icon' => 'dashicons-calendar-alt',
    );
    register_post_type('cb_event_card', $args);
}
add_action('init', 'cb_register_event_post_type');

// Adding admin menu page
function cb_add_admin_page() {
    add_submenu_page('edit.php?post_type=cb_event_card', 'Event Card Manager', 'Manage Event Cards', 'manage_options', 'cb_event_manager', 'cb_event_manager_callback');
}
add_action('admin_menu', 'cb_add_admin_page');

// Admin page callback
function cb_event_manager_callback() {
    include plugin_dir_path(__FILE__) . '/admin/admin-page.php';
}

// Shortcode to display events on any page
function cb_display_event_cards() {
    ob_start();
    include plugin_dir_path(__FILE__) . '/public/display-events.php';
    return ob_get_clean();
}
add_shortcode('cb_event_cards', 'cb_display_event_cards');

//WPBakery 
// Check if WPBakery Page Builder is activated
if (defined('WPB_VC_VERSION')) {
    add_action('vc_before_init', 'cb_integrate_event_cards_with_vc', 30);
}
add_action('vc_before_init', 'cb_integrate_event_cards_with_vc', 30);

function cb_integrate_event_cards_with_vc() {
    vc_map(array(
        "name" => __("Event Cards CB", "cb-domain"),
        "description" => __("Display event cards.", "cb-domain"),
        "base" => "cb_event_cards",
        "category" => __("Custom Elements", "cb-domain"),
        "icon" => 'dashicons-calendar-alt', // Use WordPress dashicons as an example
        "params" => array(
            // You can add custom parameters here if needed
        )
    ));
}

