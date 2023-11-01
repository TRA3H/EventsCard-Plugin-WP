<?php
/*
Plugin Name: CB Unique Event Card Generator
Description: Custom plugin to generate event cards.
Version: 1.0.5
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
    // Get current post meta data for the image
    $cb_event_image_url = get_post_meta($post->ID, '_cb_event_image_url', true);

    // Display the image upload button and hidden input field
    echo '<p>Event Image: <button id="upload_image_button" class="button">Upload Image</button></p>';
    echo '<input type="hidden" name="cb_event_image_url" id="cb_event_image_url" value="' . esc_attr($cb_event_image_url) . '">';
    if ($cb_event_image_url) {
        echo '<img src="' . esc_url($cb_event_image_url) . '" style="max-width:200px;display:block;">';
    }

    // Get current post meta data for the image size
    $cb_event_image_size = get_post_meta($post->ID, '_cb_event_image_size', true);

    // Display the dropdown for image size selection
    echo '<p>Image Size: 
        <select name="cb_event_image_size">
            <option value="thumbnail" ' . selected($cb_event_image_size, 'thumbnail', false) . '>Thumbnail</option>
            <option value="medium" ' . selected($cb_event_image_size, 'medium', false) . '>Medium</option>
            <option value="large" ' . selected($cb_event_image_size, 'large', false) . '>Large</option>
            <option value="full" ' . selected($cb_event_image_size, 'full', false) . '>Full</option>
            <option value="cb-event-large" ' . selected($cb_event_image_size, 'cb-event-large', false) . '>Custom Large (500x500)</option>
        </select>
    </p>';
    
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
    update_post_meta($post_id, '_cb_event_image_url', sanitize_text_field($_POST['cb_event_image_url']));
    update_post_meta($post_id, '_cb_event_image_size', sanitize_text_field($_POST['cb_event_image_size']));
}
add_action('save_post', 'cb_save_event_meta');

// Enqueue custom styles
function cb_enqueue_event_styles() {
    wp_enqueue_style('cb-event-style', plugin_dir_url(__FILE__) . 'cb-event-style.css');
    wp_enqueue_script('lightbox-js', 'https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox.min.js', array('jquery'), '2.11.3', true);
    wp_enqueue_style('lightbox-css', 'https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css', array(), '2.11.3');
}
add_action('wp_enqueue_scripts', 'cb_enqueue_event_styles');

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
            $event_image_url = get_post_meta(get_the_ID(), '_cb_event_image_url', true); // Fetch the image URL
            $image_size = get_post_meta(get_the_ID(), '_cb_event_image_size', true) ?: 'thumbnail'; // Fetch the image size, default to 'thumbnail' if not set
            ?>
            <div class="cb-event-card">
                <h2><?php the_title(); ?></h2>
                <p><?php echo $event_date . ' ' . $event_time; ?></p>
                <?php if ($event_image_url): ?>
                    <a href="<?php echo esc_url($event_image_url); ?>" data-lightbox="event-image">
                        <img src="<?php echo esc_url($event_image_url); ?>" alt="Event Image" style="max-width:500px;">
                    </a>
                <?php elseif (has_post_thumbnail()): ?>
                    <a href="<?php echo get_the_post_thumbnail_url(get_the_ID(), 'full'); ?>" data-lightbox="event-image">
                        <?php the_post_thumbnail($image_size); ?>
                    </a>
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

function cb_delete_past_events() {
    // Get the date from one month ago
    $one_month_ago = date('Y-m-d', strtotime('-1 month'));

    $events = new WP_Query(array(
        'post_type' => 'cb_event_card',
        'posts_per_page' => -1,
        'meta_key' => '_cb_event_date',
        'meta_value' => $one_month_ago,
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

add_filter('the_content', 'remove_image_links');
function remove_image_links($content) {
    $content = preg_replace('/<a(.*?)><img(.*?)><\/a>/i', '<img$2>', $content);
    return $content;
}

function cb_custom_image_sizes() {
    add_image_size('cb-event-large', 500, 500, true); // 500x500 pixels, cropped
}
add_action('init', 'cb_custom_image_sizes');

function enqueue_event_card_scripts() {
    // Register and enqueue the JavaScript file
    wp_enqueue_script('event-card-fullscreen', plugin_dir_url(__FILE__) . 'js/fullscreen-toggle.js', array('jquery'), '1.0.0', true);
}
add_action('wp_enqueue_scripts', 'enqueue_event_card_scripts');

function cb_enqueue_admin_scripts() {
    wp_enqueue_media();
    wp_enqueue_script('cb-admin-script', plugin_dir_url(__FILE__) . 'js/admin.js', array('jquery'), '1.0.0', true);
}
add_action('admin_enqueue_scripts', 'cb_enqueue_admin_scripts');

?>
