<?php

require_once plugin_dir_path(__FILE__) . './wcgpq_forms_templates.php';


if (!defined('ABSPATH')) {
    exit;
}



function wcgpq_display_popup_form()
{

    $wcgpq_selected_form = get_option('wcgpq_form_type','form_a');


?>
    <div id="wcgpq-popup-overlay" style="display:none;">
        <div id="wcgpq-popup-box">
            <span id="wcgpq-close-popup">&times;</span>
            <h2>Request a Quote</h2>
            <?php if ($wcgpq_selected_form === 'form_a'): ?>
                <form id="wcgpq-form">
                    <input type="hidden" name="cart_count" id="wcgpq-cart-count" value="">



                    <p>
                        <label for="wcgpq-name">Name <span style="color:red;">*</span></label><br>
                        <input type="text" name="name" id="wcgpq-name" required>
                    </p>

                    <p>
                        <label for="wcgpq-email">Email <span style="color:red;">*</span></label><br>
                        <input type="email" name="email" id="wcgpq-email" required>
                    </p>

                    <!-- <p>
                    <label for="wcgpq-quantity">Quantity</label><br>
                    <input type="number" name="quantity" id="wcgpq-quantity" value="1" min="1">
                </p> -->

                    <p>
                        <label for="wcgpq-message">Message</label><br>
                        <textarea name="message" id="wcgpq-message" rows="4"></textarea>
                    </p>

                    <p>
                        <button type="submit" class="button" id="wcgpq-submit-btn">Request Quote</button>
                    </p>
                </form>
            <?php elseif ($wcgpq_selected_form === 'form_b'): ?>
                <form id="wcgpq-form">
                    <input type="hidden" name="cart_count" id="wcgpq-cart-count" value="">



                    <p>
                        <label for="wcgpq-name">Full Name <span style="color:red;">*</span></label><br>
                        <input type="text" name="name" id="wcgpq-name" required>
                    </p>


                    <p>
                        <label for="wcgpq-email">Company Name <span style="color:red;">*</span></label><br>
                        <input type="text" name="company" id="wcgpq-company" required>
                    </p>

                    <p>
                        <label for="wcgpq-email">Bussiness Email <span style="color:red;">*</span></label><br>
                        <input type="email" name="email" id="wcgpq-email" required>
                    </p>

                    <p>
                        <label for="wcgpq-quantity">Quantity</label><br>
                        <input type="number" name="quantity" id="wcgpq-quantity" value="1" min="1">
                    </p>

                    <p>
                        <label for="wcgpq-message">Message</label><br>
                        <textarea name="message" id="wcgpq-message" rows="4"></textarea>
                    </p>

                    <p>
                        <button type="submit" class="button" id="wcgpq-submit-btn">Request Quote</button>
                    </p>
                </form>
            <?php endif ?>
            <div id="wcgpq-form-response"></div>
        </div>
    </div>
<?php


}
add_action('wp_footer', 'wcgpq_display_popup_form');
