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

    $product_id = get_the_ID();
    $product = wc_get_product($product_id);

    if (!wcgpq_woocommerce_active() || !is_product()) {
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

add_action('woocommerce_after_add_to_cart_button', 'wcgpq_add_quote_button_after_add_to_cart');

function wcgpq_load_assets()
{

    // if (is_product() || is_shop() || is_product_category() || is_product_tag()) {

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
            'nonce' => wp_create_nonce('wcgpq_product_quote_nonce')
        )
    );
    // }
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

    if (!$product_id || !$name || !$email) {
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

add_action('wp_ajax_wcgpq_send_quote', 'wcgpq_handle_quote_request');
add_action('wp_ajax_nopriv_wcgpq_send_quote', 'wcgpq_handle_quote_request');

function wcgpq_send_email($product, $name, $email, $phone, $quantity, $message)
{

    $admin_email = get_option('wcgpq_admin_email', get_option('admin_email'));

    error_log('WCGPQ: Admin Email: ' . $admin_email);

    $subject = get_option('wcgpq_email_subject', 'Product Quotation');

    error_log('WCGPQ: Attempting to send email to: ' . $admin_email);
    error_log('WCGPQ: Subject: ' . $subject);

    $template = get_option('wcgpq_email_template', '');

    if (empty($template)) {
        $template = "New Quote Request Received\n\nA customer has requested a quote for a product. Here are the details:\n\nCustomer Name: {name}\nCustomer Email: {email}\n\nProduct: {product_name}\nProduct URL: {product_link}\nQuantity: {quantity}\n\nCustomer Message:\n{message}\n\nRegards,\n{store_name}";
    }

    $product_name = $product->get_name();
    $product_link = get_permalink($product->get_id());
    $store_name = get_bloginfo('name');
    $admin_quote_link = admin_url('post.php?post=' . $product->get_id() . '&action=edit');

    $email_body = str_replace(
        array('{name}', '{email}', '{product_name}', '{product_link}', '{quantity}', '{message}', '{store_name}', '{admin_quote_link}'),
        array($name, $email, $product_name, $product_link, $quantity, $message, $store_name, $admin_quote_link),
        $template
    );

    $headers = array(
        'Content-Type: text/plain; charset=UTF-8',
        'From: ' . $store_name . ' <' . get_option('admin_email') . '>',
        'Reply-To: ' . $name . ' <' . $email . '>'
    );

    $sent = wp_mail($admin_email, $subject, $email_body, $headers);

    error_log('WCGPQ: Email sent result: ' . ($sent ? 'SUCCESS' : 'FAILED'));

    if (!$sent) {
        global $phpmailer;
        if (isset($phpmailer)) {
            error_log('WCGPQ: PHPMailer Error: ' . $phpmailer->ErrorInfo);
        }
    }


    return $sent;
}

function wcgpq_configure_smtp($phpmailer)
{
    $phpmailer->isSMTP();
    $phpmailer->Host  = 'smtp.gmail.com';
    $phpmailer->SMTPAuth = true;
    $phpmailer->Port = 587;
    $phpmailer->Username = 'huzaifamurtaza007@gmail.com';
    $phpmailer->Password = 'nqpj jkhw dmzc hnho';
    $phpmailer->SMTPSecure = 'tls';
    $phpmailer->From       = 'huzaifamurtaza007@gmail.com';
    $phpmailer->FromName   = get_bloginfo('name');
}

add_action('phpmailer_init', 'wcgpq_configure_smtp');
