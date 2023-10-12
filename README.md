# Event Card Plugin

## Overview
This WordPress plugin allows you to create and manage event cards. Each event card can have a title, description, date, time, and an image.

## Features

- **Custom Post Type**: Introduces a new post type called "Event Cards" in the WordPress dashboard.
- **Event Details**: Each event card can have a date and time.
- **Shortcode**: Use `[cb_event_cards]` to display event cards on any page or post.
- **WPBakery Integration**: If you have WPBakery Page Builder, you can use it to add event cards.
- **Automatic Deletion**: Past events are automatically deleted.

## How to Edit

1. **Add/Modify Event Cards**:
   - Go to the WordPress dashboard.
   - Navigate to the "Event Cards" section.
   - Here, you can add a new event or edit an existing one.

2. **Change Event Display Order**:
   - In the `cb_display_event_cards` function, modify the `'orderby'` and `'order'` parameters. Currently, it's set to order by date in ascending order.

3. **Styling**:
   - The plugin enqueues a stylesheet named `cb-event-style.css`. You can modify this file to change the appearance of the event cards.

4. **WPBakery Integration**:
   - If you're using WPBakery Page Builder, you can add event cards using the "Event Cards CB" element.

5. **Automatic Deletion**:
   - The plugin automatically deletes past events. If you want to disable this, remove or comment out the `add_action('wp_loaded', 'cb_delete_past_events');` line.

## Note
Always backup your current plugin file before making any changes. This documentation provides a high-level overview and basic instructions for editing. For more detailed changes, familiarity with WordPress development is recommended.
