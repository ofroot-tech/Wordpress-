<?php 

// Instructions
// Add this to your theme’s functions.php file if you want to extend WooCommerce functionality and display the custom field on the single product page.

// Options => There are variations of functionality below, activate/deactivate by commenting/uncommenting the code block.

// Option 1: Display custom field on the product page (Frontend: Show custom field)
add_action('woocommerce_single_product_summary', 'display_custom_field_on_product_page', 25);

function display_custom_field_on_product_page() {
    global $post;

    $custom_field_value = get_post_meta($post->ID, '_custom_product_field', true);

    if (!empty($custom_field_value)) {
        echo '<div class="custom-product-field">';
        echo '<p><strong>' . __('Custom Field', 'woocommerce') . ':</strong> ' . esc_html($custom_field_value) . '</p>';
        echo '</div>';
    }
}

// Option 2: Restrict display of the custom field to certain product categories... 

// function display_custom_field_on_product_page() {
//     global $post;

//     // Only show the custom field for products in a specific category
//     if (has_term('special-category', 'product_cat', $post->ID)) {
//         $custom_field_value = get_post_meta($post->ID, '_custom_product_field', true);

//         if (!empty($custom_field_value)) {
//             echo '<div class="custom-product-field">';
//             echo '<p><strong>' . __('Custom Field', 'woocommerce') . ':</strong> ' . esc_html($custom_field_value) . '</p>';
//             echo '</div>';
//         }
//     }
// }

// Option 3: Add an Option to Display Only for Logged-In Users

// function display_custom_field_on_product_page() {
//     global $post;

//     if (is_user_logged_in()) {
//         $custom_field_value = get_post_meta($post->ID, '_custom_product_field', true);

//         if (!empty($custom_field_value)) {
//             echo '<div class="custom-product-field">';
//             echo '<p><strong>' . __('Custom Field', 'woocommerce') . ':</strong> ' . esc_html($custom_field_value) . '</p>';
//             echo '</div>';
//         }
//     }
// }


// Option 4: Handle Missing or Empty Fields Gracefully

// function display_custom_field_on_product_page() {
//     global $post;

//     $custom_field_value = get_post_meta($post->ID, '_custom_product_field', true);

//     if (!empty($custom_field_value)) {
//         echo '<div class="custom-product-field">';
//         echo '<p><strong>' . __('Custom Field', 'woocommerce') . ':</strong> ' . esc_html($custom_field_value) . '</p>';
//         echo '</div>';
//     } else {
//         // Optionally display a message if the field is empty
//         echo '<div class="custom-product-field">';
//         echo '<p>' . __('No additional information available for this product.', 'woocommerce') . '</p>';
//         echo '</div>';
//     }
// }



