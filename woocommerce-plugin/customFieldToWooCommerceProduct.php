<?php

// Hook into WooCommerce product data panels (Admin: Product Editing Page)
add_action('woocommerce_product_options_general_product_data', 'my_custom_product_field');

function my_custom_product_field() {
    woocommerce_wp_text_input(array(
        'id' => '_custom_product_field',
        'label' => __('Custom Product Field', 'woocommerce'),
        'desc_tip' => 'true',
        'description' => __('Enter the custom value here.', 'woocommerce'),
        'type' => 'text'
    ));
}

// Save custom field data (Admin: Save Product Data)
add_action('woocommerce_process_product_meta', 'save_my_custom_product_field');

function save_my_custom_product_field($post_id) {
    $custom_field_value = isset($_POST['_custom_product_field']) ? sanitize_text_field($_POST['_custom_product_field']) : '';
    update_post_meta($post_id, '_custom_product_field', $custom_field_value);
}

// Add custom field value to the cart item (Backend: Adding data to Cart)
add_filter('woocommerce_add_cart_item_data', 'add_custom_field_to_cart_item', 10, 2);

function add_custom_field_to_cart_item($cart_item_data, $product_id) {
    $custom_field_value = get_post_meta($product_id, '_custom_product_field', true);

    if (!empty($custom_field_value)) {
        $cart_item_data['_custom_product_field'] = $custom_field_value;
    }

    return $cart_item_data;
}

// Add custom field to order items meta (Backend: Save to Order Meta)
add_action('woocommerce_checkout_create_order_line_item', 'add_custom_field_to_order_items', 10, 4);

function add_custom_field_to_order_items($item, $cart_item_key, $values, $order) {
    if (isset($values['_custom_product_field'])) {
        $item->add_meta_data(__('Custom Field', 'woocommerce'), $values['_custom_product_field'], true);
    }
}

// Display custom field in the order admin panel (Admin: Display in Order Details)
add_action('woocommerce_admin_order_data_after_order_details', 'display_custom_field_in_order_admin');

function display_custom_field_in_order_admin($order) {
    $items = $order->get_items();
    foreach ($items as $item_id => $item) {
        $custom_field_value = $item->get_meta('_custom_product_field');
        if ($custom_field_value) {
            echo '<p><strong>' . __('Custom Field', 'woocommerce') . ':</strong> ' . esc_html($custom_field_value) . '</p>';
        }
    }
}

// Add custom field to emails (Backend: Add to Emails)
add_filter('woocommerce_email_order_meta_fields', 'add_custom_field_to_email', 10, 3);

function add_custom_field_to_email($fields, $sent_to_admin, $order) {
    foreach ($order->get_items() as $item_id => $item) {
        $custom_field_value = $item->get_meta('_custom_product_field');
        if ($custom_field_value) {
            $fields['custom_product_field'] = array(
                'label' => __('Custom Field', 'woocommerce'),
                'value' => $custom_field_value,
            );
        }
    }
    return $fields;
}

