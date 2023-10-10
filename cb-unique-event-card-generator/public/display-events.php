<?php
// Query for the custom post type events
$events = new WP_Query(array(
    'post_type' => 'cb_event_card',
    'posts_per_page' => -1 // Display all events
));

if ($events->have_posts()) {
    while ($events->have_posts()) {
        $events->the_post();
        ?>
        <div class="cb-event-card">
            <h2><?php the_title(); ?></h2>
            <div><?php the_content(); ?></div>
        </div>
        <?php
    }
} else {
    echo "No events found.";
}
wp_reset_postdata();
