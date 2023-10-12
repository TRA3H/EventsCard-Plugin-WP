<?php
// Query for the custom post type events with ordering
$events = new WP_Query(array(
    'post_type' => 'cb_event_card',
    'posts_per_page' => -1,
    'meta_query' => array(
        'relation' => 'AND',
        'event_date_clause' => array(
            'key' => '_cb_event_date',
            'compare' => 'EXISTS',
        ),
        'event_time_clause' => array(
            'key' => '_cb_event_time',
            'compare' => 'EXISTS',
        ),
    ),
    'orderby' => array(
        'event_date_clause' => 'DESC',
        'event_time_clause' => 'DESC',
    ),
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
?>