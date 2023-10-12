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
        'supports' => array('title', 'editor', 'thumbnail'),
        'menu_icon' => 'dashicons-calendar-alt',
    );
    register_post_type('cb_event_card', $args);
}
add_action('init', 'cb_register_event_post_type');

// Adding custom meta boxes for event details
function cb_add_event_meta_boxes() {
    add_meta_box('cb_event_details', 'Event Details', 'cb_event_details_callback', 'cb_event_card', 'normal', 'high');
}
add_action('add_meta_boxes', 'cb_add_event_meta_boxes');

function cb_event_details_callback($post) {
    // Nonce for security
    wp_nonce_field(basename(__FILE__), 'cb_event_nonce');
    
    // Get current post meta data
    $cb_event_date = get_post_meta($post->ID, '_cb_event_date', true);
    $cb_event_time = get_post_meta($post->ID, '_cb_event_time', true);
    
    // Display the fields
    echo '<p>Date: <input type="date" name="cb_event_date" value="' . esc_attr($cb_event_date) . '"></p>';
    echo '<p>Time: <input type="time" name="cb_event_time" value="' . esc_attr($cb_event_time) . '"></p>';
}

// Save the custom fields data
function cb_save_event_meta($post_id) {
    // Check nonce for security
    if (!isset($_POST['cb_event_nonce']) || !wp_verify_nonce($_POST['cb_event_nonce'], basename(__FILE__))) {
        return $post_id;
    }

    // Save each custom field value
    update_post_meta($post_id, '_cb_event_date', sanitize_text_field($_POST['cb_event_date']));
    update_post_meta($post_id, '_cb_event_time', sanitize_text_field($_POST['cb_event_time']));
}
add_action('save_post', 'cb_save_event_meta');

// Enqueue custom styles
function cb_enqueue_event_styles() {
    wp_enqueue_style('cb-event-style', plugin_dir_url(__FILE__) . 'cb-event-style.css');
}
add_action('wp_enqueue_scripts', 'cb_enqueue_event_styles');

// Shortcode to display events on any page
function cb_display_event_cards() {
    ob_start();
    
    // Query for the custom post type events
    $events = new WP_Query(array(
        'post_type' => 'cb_event_card',
        'posts_per_page' => -1,
        'meta_key' => '_cb_event_date',
        'orderby' => 'meta_value',
        'order' => 'ASC'
    ));

    if ($events->have_posts()) {
        while ($events->have_posts()) {
            $events->the_post();
            $event_date = get_post_meta(get_the_ID(), '_cb_event_date', true);
            $event_time = get_post_meta(get_the_ID(), '_cb_event_time', true);
            ?>
            <div class="cb-event-card">
                <h2><?php the_title(); ?></h2>
                <p><?php echo $event_date . ' ' . $event_time; ?></p>
                <?php if (has_post_thumbnail()) : ?>
                    <div class="cb-event-image">
                        <?php the_post_thumbnail('full'); ?>
                    </div>
                <?php endif; ?>
                <div><?php the_content(); ?></div>
            </div>
            <?php
        }
    } else {
        echo "No events found.";
    }
    wp_reset_postdata();
    
    return ob_get_clean();
}
add_shortcode('cb_event_cards', 'cb_display_event_cards');

//WPBakery 
// Check if WPBakery Page Builder is activated
if (defined('WPB_VC_VERSION')) {
    add_action('vc_before_init', 'cb_integrate_event_cards_with_vc', 30);
}

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

// Automatically delete past events
function cb_delete_past_events() {
    $current_date = date('Y-m-d');
    $events = new WP_Query(array(
        'post_type' => 'cb_event_card',
        'posts_per_page' => -1,
        'meta_key' => '_cb_event_date',
        'meta_value' => $current_date,
        'meta_compare' => '<',
        'orderby' => 'meta_value',
        'order' => 'ASC'
    ));

    if ($events->have_posts()) {
        while ($events->have_posts()) {
            $events->the_post();
            wp_delete_post(get_the_ID(), true);
        }
    }
    wp_reset_postdata();
}
add_action('wp_loaded', 'cb_delete_past_events');
?>
