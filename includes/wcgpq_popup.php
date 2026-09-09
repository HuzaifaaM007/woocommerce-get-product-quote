<?php

require_once plugin_dir_path(__FILE__) . './wcgpq_forms_templates.php';
require_once plugin_dir_path(__FILE__) . '../admin/wcgpq-form-builder.php';


if (!defined('ABSPATH')) {
    exit;
}



function wcgpq_display_popup_form()
{

    $wcgpq_selected_form = get_option('wcgpq_form_type', 'form_a');


?>
    <div id="wcgpq-popup-overlay" style="display:none;">
        <div id="wcgpq-popup-box">
            <span id="wcgpq-close-popup">&times;</span>
            <h2>Request a Quote</h2>
            <?php if ($wcgpq_selected_form): ?>
                <form id="wcgpq-form">
                    <?php if (is_cart()): ?>
                        <input type="hidden" name="cart_count" id="wcgpq-cart-count" value="">
                        <input type="hidden" name="is_cart" id="wcgpq-is-cart" value="1">
                    <?php elseif (is_shop() || is_product()) : ?>
                        <input type="hidden" name="product_id" id="wcgpq-product-id" value="">
                        <input type="hidden" name="is_product" id="wcgpq-is-product" value="1">
                    <?php endif; ?>
                    <?php echo wcgpq_render_forms($wcgpq_selected_form) ?>
                    <p>
                        <button type="submit" class="button" id="wcgpq-submit-btn">Request Quote</button>
                    </p>
                </form>
            <?php endif; ?>
            <div id="wcgpq-form-response"></div>
        </div>
    </div>
<?php


}
add_action('wp_footer', 'wcgpq_display_popup_form');
