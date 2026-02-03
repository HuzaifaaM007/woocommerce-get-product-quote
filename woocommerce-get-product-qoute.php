<?php

/**
 * Plugin Name: woocommerce get product qoute 
 * Plugin URI: https://woocommeregetproductqoute.com
 * Description: Wooommerce Plugin to get product qoutes from customers using email
 * Version: 1.0
 * Author: Huzaifa
 * Author URI: Huzaifa_Murtaza.com
 * Text Domain: woocommere-get-product-qoute
 */

require_once plugin_dir_path(__FILe__) . 'admin/settings.php';


if (!defined('ABSPATH')) {
    exit;
}

function wcgpq_woocomerce_active()
{
    return class_exists('WooCommerce');
}


function wcgpq_wc_missing_notice()
{
    if (!wcgpq_woocomerce_active()) {
        echo '<div class="notice notice-error">
                <p><strong>WooCommerce Plugin Message:</strong> WooCommerce must be active.</p>
              </div>';
    }
}

add_action('admin_notices', 'wpgq_wc_missing_notice');


function wcgpq_add_qoute_button_after_product_card()
{

    if (!wcgpq_woocomerce_active()) {
        return;
    }

    $product = wc_get_product(get_the_ID());

    if (!$product) {
        return;
    }

    $wcgpq_location = get_option('wcgpq_locations', []);

    if (!array($wcgpq_location)) {
        return;
    }

    if (($wcgpq_location['product_list'] ?? 'no') === 'yes') {

        ?> 
        <button
        type="button"
        class="wcgpq-button button"
        data-product-name ="<?php echo esc_attr($product->get_name());?>"
        data-product-price = "<?php echo esc_attr($product->get_price());?>"
        data-product-sku ="<?php echo esc_attr($product->get_sku());?>"
        data-product-url = "<?php echo esc_attr(get_permalink($product->get_id()));?>"
        
        > Get Qoute</button>
        <?php
    }
}

add_action('woocommerce_after_shop_loop_item','wcgpq_add_qoute_button_after_product_card');


