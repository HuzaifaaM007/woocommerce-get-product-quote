<?php

/**
 * Plugin Name: woocommerce get product quote 
 * Plugin URI: https://woocommeregetproductquote.com
 * Description: Wooommerce Plugin to get product quotes from customers using email
 * Version: 1.0
 * Author: Huzaifa
 * Author URI: Huzaifa_Murtaza.com
 * Text Domain: woocommere-get-product-quote
 */

require_once plugin_dir_path(__FILE__) . 'admin/settings.php';
require_once plugin_dir_path(__FILE__) . 'includes/wcgpq_popup.php';


if (!defined('ABSPATH')) {
    exit;
}

function wcgpq_woocommerce_active()
{
    return class_exists('WooCommerce');
}


function wcgpq_wc_missing_notice()
{
    if (!wcgpq_woocommerce_active()) {
        echo '<div class="notice notice-error">
                <p><strong>WooCommerce Plugin Message:</strong> WooCommerce must be active.</p>
              </div>';
    }
}

add_action('admin_notices', 'wcgpq_wc_missing_notice');


function wcgpq_add_quote_button_after_product_card()
{

    $product = wc_get_product(get_the_ID());

    if (!wcgpq_woocommerce_active() || !$product) {
        return;
    }



    $wcgpq_locations = get_option('wcgpq_locations', []);

    if (!is_array($wcgpq_locations)) {
        return;
    }

    if (($wcgpq_locations['product_list'] ?? 'no') === 'yes') {

?>
        <button
            type="button"
            class="wcgpq-button button"
            data-product-id="<?php echo $product->get_id() ?>">
            <!-- data-product-name="<?php echo esc_attr($product->get_name()); ?>"
            data-product-price="<?php echo esc_attr($product->get_price()); ?>"
            data-product-sku="<?php echo esc_attr($product->get_sku()); ?>" -->
            <!-- data-product-url="<?php echo esc_attr(get_permalink($product->get_id())); ?>"> -->
            Get quote</button>


    <?php
    }
}

add_action('woocommerce_after_shop_loop_item', 'wcgpq_add_quote_button_after_product_card');

function wcgpq_add_quote_button_after_add_to_cart()
{

    $product = wc_get_product(get_the_ID());

    if (!wcgpq_woocommerce_active() || !$product) {
        return;
    }

    $wcgpq_locations = get_option('wcgpq_locations', []);

    if (!is_array($wcgpq_locations)) {
        return;
    }

    if (($wcgpq_locations['product_details'] ?? 'no') === 'yes') {
    ?>

        <button
            type="button"
            class="wcgpq-button button"
            data-product-id="<?php echo $product->get_id() ?>">
            <!-- data-product-name="<?php echo esc_attr($product->get_name()); ?>" -->
            <!-- data-product-price="<?php echo esc_attr($product->get_price()); ?>" -->
            <!-- data-product-sku="<?php echo esc_attr($product->get_sku()); ?>" -->
            <!-- data-product-url="<?php echo esc_attr(get_permalink($product->get_id())); ?>" -->

            Get Quote</button>
<?php
    }
}

add_action('woocommerce_after_add_to_cart', 'wcgpq_add_quote_button_after_add_to_cart');

function wcgpq_load_assets()
{

    if (is_product() || is_shop() || is_product_category() || is_product_tag()) {

        wp_enqueue_script(
            'wcgpq-product-quote-button-js',
            plugin_dir_url(__FILE__) . 'assets/js/wcgpq.js',
            array(),
            '1.0',
            true
        );

        wp_enqueue_style(
            'wcgpq-product-quote-button-css',
            plugin_dir_url(__FILE__) . 'assets/css/wcgpq.css',
            array(),
            '1.0'
        );

        wp_localize_script(
            'wcgpq-product-quote-button-js',
            'wcgpq_product_quote_data',
            array(
                'ajaxurl' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('wcgpq-product_quote_data_nonce')
            )
        );
    }
}

add_action('wp_enqueue_scripts', 'wcgpq_load_assets');

function wcgpq_handle_quote_request()
{

    if (!isset($_POST['nonce'])  || !wp_verify_nonce($_POST['nonce'], 'wcgpq_product_quote_nonce')) {
        wp_send_json_error('Security Check Failed');
        return;
    }

    // Sanitize and validate form data
    $product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
    $name = isset($_POST['name']) ? sanitize_text_field($_POST['name']) : '';
    $email = isset($_POST['email']) ? sanitize_email($_POST['email']) : '';
    $phone = isset($_POST['phone']) ? sanitize_text_field($_POST['phone']) : '';
    $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;
    $message = isset($_POST['message']) ? sanitize_textarea_field($_POST['message']) : '';

    if (!$product_id || !$name || !$email || !$phone) {
        wp_send_json_error('Fill all required fields');
        return;
    }

    if (!is_email($email)) {
        wp_send_json_error('Invalid email address');
        return;
    }

    $product = wc_get_product($product_id);

    if (!$product) {
        wp_send_json_error('Product not found!');
        return;
    }

    $email_sent = wcgpq_send_email($product, $name, $email, $phone, $quantity, $message);

    if ($email_sent) {
        wp_send_json_success('Quote request send succesfully');
    } else {
        wp_send_json_error('Failed to send email. Please try again.');
    }
}

function wcgpq_send_email($product, $name, $email, $phone, $quantity, $message) {
    
}
