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

// add_action('woocommerce_after_shop_loop_item', 'wcgpq_add_quote_button_after_product_card');

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

// add_action('woocommerce_after_add_to_cart_button', 'wcgpq_add_quote_button_after_add_to_cart');


function wcgpq_add_quote_button_after_check_out_button()
{

    if (!wcgpq_woocommerce_active()) {
        return;
    }

    // $product_id = get_the_ID();

    // $product = wc_get_product($product_id);

    $wcgpq_locations = get_option('wcgpq_locations', []);

    if (!is_array($wcgpq_locations) || wc()->cart->is_empty()) {
        return;
    }

    error_log($wcgpq_locations['cart']);

    $cart_items = [];

    foreach (wc()->cart->get_cart() as $cart_item) {
        $product = $cart_item['data'];

        $cart_items[] = [
            'name' => $product->get_name(),
            'price' => $product->get_price(),
            'quantity' => $cart_item['quantity'],
            'subtotal' => $cart_item['line_total'],
            'tax' => $cart_item['line_tax'],
            'sku' => $product->get_sku(),
            'url' => get_permalink($product->get_id()),
        ];
    }

    if (($wcgpq_locations['cart'] ?? 'no') === 'yes') {
    ?>
        <button
            type="button"
            class="wcgpq-cart-button button"
            style="
            padding-left: 1em;
            font-size: 1.1em;
            line-height :1.8em;
            display:block
            "
            data-cart-count="<?php echo count($cart_items);
                                error_log("get quote button cart pressed " . print_r($cart_items, true)) ?>">
            Get Quote
        </button>
    <?php
    }
}

add_action('woocommerce_after_cart_totals', 'wcgpq_add_quote_button_after_check_out_button');

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
        '1.0',
        'all'
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
    $cart_count = isset($_POST['cart_count']) ? intval($_POST['cart_count']) : 0;
    $name = isset($_POST['name']) ? sanitize_text_field($_POST['name']) : '';
    $email = isset($_POST['email']) ? sanitize_email($_POST['email']) : '';
    $phone = isset($_POST['phone']) ? sanitize_text_field($_POST['phone']) : '';
    $company = isset($_POST['company']) ? sanitize_text_field($_POST['company']) : '';
    $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;
    $message = isset($_POST['message']) ? sanitize_textarea_field($_POST['message']) : '';

    if (!$cart_count || !$name || !$email) {
        wp_send_json_error('Fill all required fields');
        return;
    }

    if (!is_email($email)) {
        wp_send_json_error('Invalid email address');
        return;
    }

    if ($cart_count <= 0) {
        wp_send_json_error("cart is empty can't process");
        return;
    }


    // $product = wc_get_product($product_id);

    // if (!$product) {
    //     wp_send_json_error('Product not found!');
    //     return;
    // }

    $cart_items = wc()->cart->get_cart();


    $cart_items_text = "";
    $total_quantity = 0;

    foreach ($cart_items as  $cart_item) {
        $product = $cart_item['data'];
        $product_name = $product->get_name();
        $product_link = get_permalink($product->get_id());
        $admin_quote_link = admin_url('post.php?post=' . $product->get_id() . '&action=edit');
        $quantity = $cart_item['quantity'];
        $total_quantity += $quantity;

        $cart_items_text .= "- {$product_name} (Qty: {$quantity})\n  URL: {$product_link}\n Admin Link: {$admin_quote_link}\n\n";
    }

    $post_data = array(
        'post_title' => $name . '-Quote Request',
        'post_content' =>  "Customer Email: " . $email . "\n\n  Cart_data:\n " . $cart_items_text . "\n\n  Customer Message: \n" . $message,
        'post_status' => 'publish',
        'post_type' => 'quote_request',

    );

    $email_sent = wcgpq_send_email($cart_items, $name, $email, $phone, $company, $quantity, $message);

    if ($email_sent) {
        wcgpq_insert_quote_request_post($post_data, $email);

        wp_send_json_success('Quote request send succesfully');
    } else {
        wp_send_json_error('Failed to send email. Please try again.');
    }
}

add_action('wp_ajax_wcgpq_send_quote', 'wcgpq_handle_quote_request');
add_action('wp_ajax_nopriv_wcgpq_send_quote', 'wcgpq_handle_quote_request');

function wcgpq_send_email($cart_items, $name, $email, $phone, $company, $quantity, $message)
{

    $admin_email = get_option('wcgpq_admin_email', get_option('admin_email'));

    error_log('WCGPQ: Admin Email: ' . $admin_email);

    $subject = get_option('wcgpq_email_subject', 'Product Quotation');

    error_log('WCGPQ: Attempting to send email to: ' . $admin_email);
    error_log('WCGPQ: Subject: ' . $subject);

    $template = get_option('wcgpq_email_template', '');

    if (empty($template)) {
        $template = "New Quote Request Received\n\nA customer has requested a quote for their cart. Here are the details:\n\nCustomer Name: {name}\nCustomer Email: {email}\n" . (!empty($company) ? "Company: {company}\n" : "") . "Phone: {phone}\n\nRequested Items:\n{cart_items}\n\nTotal Quantity: {total_quantity}\n\nCustomer Message:\n{message}\n\nRegards,\n{store_name}";
    }

    $cart_items_text = "";
    $total_quantity = 0;

    foreach ($cart_items as  $cart_item) {
        $product = $cart_item['data'];
        $product_name = $product->get_name();
        $product_link = get_permalink($product->get_id());
        $admin_quote_link = admin_url('post.php?post=' . $product->get_id() . '&action=edit');
        $quantity = $cart_item['quantity'];
        $total_quantity += $quantity;

        $cart_items_text .= "- {$product_name} (Qty: {$quantity})\n  URL: {$product_link}\n Admin Link: {$admin_quote_link}\n\n";
    }

    $store_name = get_bloginfo('name');
    $admin_quote_link = admin_url('post.php?post=' . $product->get_id() . '&action=edit');

    $email_body = str_replace(
        array('{name}', '{email}', '{cart_items}', '{company}', '{total_quantity}', '{message}', '{store_name}'),
        array($name, $email, $cart_items_text, $company, $total_quantity, $message, $store_name),
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
    $phpmailer->Username = get_option('wcgpq_mailer_username'); //'huzaifamurtaza007@gmail.com'
    $phpmailer->Password = get_option('wcgpq_mailer_password'); //'nqpj jkhw dmzc hnho'
    $phpmailer->SMTPSecure = 'tls';
    $phpmailer->From       = get_option('wcgpq_sender_mail'); //'huzaifamurtaza007@gmail.com'
    $phpmailer->FromName   = get_bloginfo('name');
}

add_action('phpmailer_init', 'wcgpq_configure_smtp');

function wcgpq_create_quote_request_cpt()
{

    $labels = array(
        'name' => __('Quote Requests', 'textdomain'),
        'singular' => __('Quote Request', 'textdomain')
    );

    $args = array(
        'labels' => $labels,
        'public' => false,
        'show_ui' => true,
        'show_in_menu' => true,
        'menu_position' => 26,
        'capability_type' => 'post',
        'supports' => array('title', 'editor', 'custom-fields'),

    );

    register_post_type('quote_request', $args);
}

add_action('init', 'wcgpq_create_quote_request_cpt');

function wcgpq_insert_quote_request_post($post_data, $email)
{

    $post_id = wp_insert_post($post_data);

    if ($post_id) {
        update_post_meta($post_id, '_customer_email', $email);
    }

    return $post_id;
}

function wcgpq_add_meta_box()
{

    add_meta_box(
        'wcgpq_qote_request_details',
        'Quote Request Details',
        'wcgpq_render_meta_boxes',
        'quote_request',
        'normal',
        'high'
    );
}

add_action('add_meta_boxes', 'wcgpq_add_meta_box');

function wcgpq_render_meta_boxes($post)
{
    wp_nonce_field('wcgpq_save_meta', 'wcgpq_meta_box_nonce');

    $email = get_post_meta($post->ID, '_customer_email', true);

    ?>
    <table class="form-table">
        <tr>
            <th>
                <label for="customer_email">Customer Email: </label>
            </th>
            <td>
                <input
                    type="email"
                    name="customer_email"
                    id="customer_email"
                    value="<?php echo esc_attr($email); ?>"
                    class="regular-text">
            </td>
        </tr>
    </table>
<?php

}

function wcgpq_save_meta_box_data($post_id)
{
    if (!isset($_POST['wcgpq_meta_box_nonce'])) {
        return;
    }

    if (!wp_verify_nonce($_POST['wcgpq_meta_box_nonce'], 'wcgpq_save_meta')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    if (isset($_POST['customer_email'])) {
        update_post_meta($post_id, '_customer_email', sanitize_email($_POST['customer_email']));
    }
}

add_action('save_post', 'wcgpq_save_meta_box_data');
