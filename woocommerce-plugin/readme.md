# WooCommerce Custom Product Field Plugin

This plugin allows you to add a custom field to WooCommerce products, display the field on the frontend product page, save the custom field value in cart items and orders, display the custom field in the WooCommerce admin panel, and include the field in order confirmation emails.

## Features

- Adds a custom field to the product data panel in WooCommerce.
- Saves custom field data in the product meta and persists it across cart items and orders.
- Displays the custom field on the frontend product page.
- Saves the custom field data in WooCommerce orders and displays it in the WooCommerce admin.
- Adds the custom field to the order confirmation emails sent to customers and admins.

## Installation

1. Download the plugin file and upload it to your WordPress `/wp-content/plugins/` directory.
2. Activate the plugin from the WordPress admin area under **Plugins**.

## Code Breakdown

### 1. Add Custom Field to WooCommerce Products (Admin)

This part of the code adds a custom field to the WooCommerce product admin panel, allowing users to enter data for each product.

```php
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
```

### 2. Handle Custom Field in Cart and Orders

This section handles passing the custom field from the product page to the cart, and later saving it in the order meta when a purchase is made.

```php
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
```

### 3. Display Custom Field in Admin Order Panel

This part displays the custom field in the WooCommerce admin area when viewing order details.

```php
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
```

### 4. Display Custom Field on the Product Page (Frontend)

This section displays the custom field on the single product page for customers to view. **Add this code to your theme's `functions.php` file** (or you can keep it in the plugin if you want everything in one place).

```php
// Display custom field on the product page (Frontend: Show custom field)
add_action('woocommerce_single_product_summary', 'display_custom_field_on_product_page', 25);

function display_custom_field_on_product_page() {
    global $post;

    $custom_field_value = get_post_meta($post->ID, '_custom_product_field', true);

    if (!empty($custom_field_value)) {
        echo '<p><strong>' . __('Custom Field', 'woocommerce') . ':</strong> ' . esc_html($custom_field_value) . '</p>';
    }
}
```

### 5. Add Custom Field to Order Emails

This part includes the custom field in the order confirmation emails sent to customers and admins.

```php
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
```

## File Locations

- **Plugin File (Custom Plugin)**: Place the majority of the code in a custom plugin file (e.g., `my-custom-woocommerce-plugin.php`), and upload it to `/wp-content/plugins/`.
- **Frontend Display Code**: The function `display_custom_field_on_product_page` can either be placed in your plugin or, more appropriately, in your theme’s `functions.php` file to display the custom field on the product page.

### Note:
You can expand the plugin by adding more features, such as custom taxonomies, meta boxes, and more complex product options, depending on your needs.

## How to Use

1. Add custom field data to the WooCommerce product in the product edit screen.
2. The custom field will be displayed on the product page (if the frontend code is added to the theme).
3. The custom field value will be passed through the cart and saved in the order details.
4. The custom field will be displayed in the admin panel under each order.
5. The custom field will also appear in the order confirmation emails sent to both customers and admins.

