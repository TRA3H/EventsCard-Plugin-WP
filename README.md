# CB Unique Event Card Generator

The CB Unique Event Card Generator is a custom WordPress plugin designed to generate and display event cards. It provides a user-friendly interface for creating event cards with details such as event date, time, and images. The plugin also integrates with WPBakery Page Builder and offers responsive design for various screen sizes.

## Table of Contents

- [Features](#features)
- [Usage](#usage)
- [Files Overview](#files-overview)
- [Function Reference](#function-reference)
- [Customization Guide](#customization-guide)
- [Author](#author)

## Features

- **Custom Post Type for Events**: Allows users to create and manage event cards as a custom post type in the WordPress dashboard.
- **Event Details Meta Box**: Provides fields in the WordPress editor to input event details like date, time, and images.
- **Responsive Design**: The event cards are designed to be responsive, adjusting their layout and design based on the screen size.
- **Integration with WPBakery**: The plugin integrates with WPBakery Page Builder, allowing users to add event cards using the visual builder.
- **Automatic Deletion of Past Events**: The plugin automatically deletes past events, ensuring that only upcoming events are displayed.
- **Custom Image Sizes**: Users can define custom image sizes for event images.
- **Lightbox Integration**: Event images can be viewed in a lightbox for a better user experience.

## Usage

1. **Creating an Event Card**: Navigate to the "Event Cards" post type in the WordPress dashboard and click "Add New". Fill in the event details and publish.
2. **Displaying Event Cards**: Use the shortcode `[cb_event_cards]` in your posts or pages to display the event cards.
3. **WPBakery Integration**: If you have WPBakery Page Builder activated, you can add the "Event Cards CB" element to your pages to display the event cards.

## Files Overview

- **Main Plugin File**: Contains the main plugin functionalities including registering the custom post type, adding meta boxes, and more.
- **Admin Page**: (Content not provided)
- **Event Styles**: Contains the CSS styles for the event cards.
- **Admin JS**: Contains JavaScript functionalities for the admin dashboard.
- **Fullscreen Toggle JS**: Contains JavaScript for toggling fullscreen for event images.
- **Display Events**: Contains the PHP code for querying and displaying the event cards.

## Function Reference

Below is a list of functions defined in the plugin and their purpose:

- `cb_register_event_post_type()`: Registers the custom post type 'Event Cards' for creating event cards.
- `cb_add_event_meta_boxes()`: Adds custom meta boxes for event details in the post editor.
- `cb_event_details_callback()`: Callback function for displaying the event details meta box fields.
- `cb_save_event_meta()`: Saves the custom fields data when an event card is saved or updated.
- `cb_enqueue_event_styles()`: Enqueues custom styles and scripts for the event cards on the front end.
- `cb_display_event_cards()`: Shortcode function for displaying the event cards on the front end.
- `cb_integrate_event_cards_with_vc()`: Integrates the event cards with WPBakery Page Builder.
- `cb_delete_past_events()`: Deletes event cards that are past their event date.
- `remove_image_links()`: Filter function to remove links from images in the content.
- `cb_custom_image_sizes()`: Adds custom image sizes for event images.
- `enqueue_event_card_scripts()`: Enqueues scripts for the event cards on the front end.
- `cb_enqueue_admin_scripts()`: Enqueues scripts for the admin dashboard functionalities.

## Customization Guide

If you wish to modify or customize the plugin, here's a brief guide:

1. **Changing Event Card Styles**: Navigate to `cb-event-style.css` to modify the CSS styles of the event cards.
2. **Adding New Admin Functionalities**: If you wish to add new functionalities to the admin dashboard, modify the `admin.js` file.
3. **Modifying Event Display Logic**: The `display-events.php` file contains the logic for querying and displaying the event cards. Modify this file if you wish to change how events are displayed.
4. **Adding New Event Details**: To add new fields to the event details meta box, modify the main plugin file and look for the section that registers the meta box.

## Author

Cyrus Baybay
