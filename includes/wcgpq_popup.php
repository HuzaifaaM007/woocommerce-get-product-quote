<?php

if (!defined('ABSPATH')) {
    exit;
}

function wcgpq_display_popup_form(){
   
    ?>
    <div id="wcgpq-popup-overlay" style="display:none;">
        <div id="wcgpq-popup-box">
            <span id="wcgpq-close-popup">&times;</span>
            <h2>Request a Quote</h2>
            <form id="wcgpq-form">
                <input type="hidden" name="product_id" id="wcgpq-product-id" value="">
                
                <p>
                    <label for="wcgpq-name">Name</label><br>
                    <input type="text" name="name" id="wcgpq-name" required>
                </p>

                <p>
                    <label for="wcgpq-email">Email</label><br>
                    <input type="email" name="email" id="wcgpq-email" required>
                </p>

                <p>
                    <label for="wcgpq-comments">Comments</label><br>
                    <textarea name="comments" id="wcgpq-comments" rows="4" required></textarea>
                </p>

                <p>
                    <button type="submit" class="button">Submit Quote</button>
                </p>
            </form>
            <div id="wcgpq-form-response"></div>
        </div>
    </div>
    <?php
}
add_action('wp_footer', 'wcgpq_display_popup_form');
 


