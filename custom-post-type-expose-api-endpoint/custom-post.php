<?php
/**
 * Plugin Name: Custom Post Type and API Example
 * Description: A simple plugin to create a custom post type and expose an API endpoint.
 * Version: 1.0
 * Author: Your Name
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

// Register Custom Post Type
function create_custom_post_type() {
    $labels = array(
        'name'                  => _x('Books', 'Post Type General Name', 'text_domain'),
        'singular_name'         => _x('Book', 'Post Type Singular Name', 'text_domain'),
        'menu_name'             => __('Books', 'text_domain'),
        'name_admin_bar'        => __('Book', 'text_domain'),
        'add_new_item'          => __('Add New Book', 'text_domain'),
        'edit_item'             => __('Edit Book', 'text_domain'),
        'new_item'              => __('New Book', 'text_domain'),
        'view_item'             => __('View Book', 'text_domain'),
        'all_items'             => __('All Books', 'text_domain'),
        'search_items'          => __('Search Books', 'text_domain'),
    );

    $args = array(
        'labels'                => $labels,
        'public'                => true,
        'has_archive'           => true,
        'supports'              => array('title', 'editor', 'thumbnail'),
        'show_in_rest'          => true, // Enable Gutenberg and REST API support.
    );

    register_post_type('book', $args);
}
add_action('init', 'create_custom_post_type');

// Register a Shortcode to Display Books
function display_books_shortcode($atts) {
    $atts = shortcode_atts(array(
        'posts_per_page' => '5',
    ), $atts, 'display_books');

    $query = new WP_Query(array(
        'post_type' => 'book',
        'posts_per_page' => $atts['posts_per_page'],
    ));

    if ($query->have_posts()) {
        $output = '<ul class="book-list">';
        while ($query->have_posts()) {
            $query->the_post();
            $output .= '<li><a href="' . get_the_permalink() . '">' . get_the_title() . '</a></li>';
        }
        $output .= '</ul>';
        wp_reset_postdata();
        return $output;
    } else {
        return '<p>No books found.</p>';
    }
}
add_shortcode('display_books', 'display_books_shortcode');

// Create a REST API Endpoint
add_action('rest_api_init', function () {
    register_rest_route('custom/v1', '/books', array(
        'methods' => 'GET',
        'callback' => 'get_books_api',
    ));
});

function get_books_api($data) {
    $args = array(
        'post_type' => 'book',
        'posts_per_page' => 10,
    );
    $query = new WP_Query($args);

    $books = array();

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            $books[] = array(
                'title' => get_the_title(),
                'content' => get_the_content(),
                'link' => get_the_permalink(),
            );
        }
        wp_reset_postdata();
    }

    if (empty($books)) {
        return new WP_Error('no_books', 'No books found', array('status' => 404));
    }

    return rest_ensure_response($books);
}
?>