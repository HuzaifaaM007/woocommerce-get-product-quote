<?php

/**
 * register plugin settings in Woo Commerce admin
 * 
 * Adds a setting section where admins can 
 * enable/disable woocommerce-get-product-qoute
 * 
 */


require_once plugin_dir_path(__FILE__) . '../includes/wcgpq_forms_templates.php';


add_action('woocommerce_admin_field_wcgpq_preview', 'wcgpq_render_preview_fields', 10);
error_log("=== HOOK REGISTERED: woocommerce_admin_field_wcgpq_preview ===");

function wcgpq_render_preview_fields($value)
{
    error_log("value received " . print_r($value, true));

    $form_id = isset($value['form_id']) ? $value['form_id'] : 'form_a';
    error_log('form_id : ' . $form_id);
?>
    <tr valign="top">
        <th scope="row" class="titledesc"></th>
        <td class="forminp">
            <?php echo wcgpq_generate_form_preview($form_id) ?>
        </td>
    </tr>
<?php
}



// add setting fields
function wcgpq_admin_settings()
{

    // settings code
    $wcgpq_settings_array = array();

    $wcgpq_settings_array[] =  array(
        'title' => __('WooCommerce Get Product Quote', 'wcgpq'),
        'type' => 'title',
        'desc' => __('Adds the gets qoute option on products ', 'wcgpq'),
        'id' => 'wcgpq_settings_title'
    );
    // array(
    //     'title' => __('Get product quote listing page', 'wcgpq'),
    //     'desc' => __('Add get product quote in listing page for each product', 'wcgpq'),
    //     // 'id' => 'wcgpq_locations[product_list]',
    //     'id' => 'wcgpq_location_product_list',
    //     'type' => 'checkbox',
    //     'default' => 'no'

    // ),
    // array(
    //     'title' => __('Get product quote details page', 'wcgpq'),
    //     'desc' => __('Add get product quote in details page of each product', 'wcgpq'),
    //     // 'id' => 'wcgpq_locations[product_details]'
    //     'id' => 'wcgpq_location_product_details',
    //     'type' => 'checkbox',
    //     'default' => 'no'

    // ),

    $wcgpq_settings_array[] =   array(
        'title' => __('Get product quote cart page', 'wcgpq'),
        'desc' => __('Add get product quote in shop page for each product', 'wcgpq'),
        // 'id' => 'wcgpq_locations[product_list]',
        'id' => 'wcgpq_location_product_list',
        'type' => 'checkbox',
        'default' => 'no'

    );

    $wcgpq_settings_array[] =   array(
        'title' => __('Get product quote cart page', 'wcgpq'),
        'desc' => __('Add get product quote in product details page', 'wcgpq'),
        // 'id' => 'wcgpq_locations[product_list]',
        'id' => 'wcgpq_location_product_details',
        'type' => 'checkbox',
        'default' => 'no'

    );

    $wcgpq_settings_array[] =   array(
        'title' => __('Get product quote cart page', 'wcgpq'),
        'desc' => __('Add get product quote in listing page for all products in cart', 'wcgpq'),
        // 'id' => 'wcgpq_locations[product_list]',
        'id' => 'wcgpq_location_cart',
        'type' => 'checkbox',
        'default' => 'no'

    );

    $wcgpq_settings_array[] = array(
        'title' => __('Get product quote form', 'wcgpq'),
        'desc' => __('Select the type of the form to display for getting product quote', 'wcgpq'),
        'id' => 'wcgpq_form_type',
        'type' => 'select',
        'options' => wcgpq_get_form_choices(),
        'default' => 'form_a',
        'desc_tip' => true,

    );

    $wcgpq_settings_array[] = array(
        'type' => 'sectionend',
        'id' => 'wcgpq_form_section_end'
    );

    $wcgpq_settings_array[] = array(
        'title' => 'Form Preview',
        'type' => 'title',
        'desc' => 'Below is how your selected form will look',
        'id' => 'wcgpq_form_preview_header',
        'desc_tip' => true
    );

    error_log("Adding wcgpq_preview field type to settings array");


    $wcgpq_settings_array[] = array(
        'type' => 'wcgpq_preview',
        'id' => 'wcgpq_preview',
        /**removed these as in description wp_sanitization remove the input fields of the form*/
        // 'desc' => wcgpq_generate_form_preview('form_a')
        'form_id' => 'form_a'

    );

    error_log("Form A preview added");

    // $wcgpq_settings_array[] = array(
    //     'type' => 'sectionend',
    //     'id' => 'wcgpq_preview_form_a_end'

    // );

    $wcgpq_settings_array[] = array(
        'type' => 'wcgpq_preview',
        'id' => 'wcgpq_preview',
        // 'desc' => wcgpq_generate_form_preview('form_b')
        'form_id' => 'form_b'
    );

    error_log("Form B preview added");

    // $wcgpq_settings_array[] = array(
    //     'type' => 'sectionend',
    //     'id' => 'wcgpq_preview_form_b_end'

    // );

    // array(
    //     'type' => 'title',
    //     'id' => 'wcgpq_form-1_preview',
    //     'desc' => '
    //         <div class="wcgpq-form-preview wcgpq-form-1">
    //             <h3>Form A Preview</h3>
    //             <p><input type="text" placeholder="Username" disabled></p>
    //             <p><input type="email" placeholder="Email" disabled></p>
    //             <p><input type="text" placeholder="Phone" disabled></p>
    //             <p><input type="text" placeholder="Product" disabled></p>
    //         </div>',
    // ),
    // array(
    //     'type' => 'sectioned',
    //     'id' => 'wcgpq_form_1_preview_end'
    // ),

    $wcgpq_settings_array[] = array(
        'title' => __('Mailing Host', 'wcgpq'),
        'desc' => __('Enter the hosting service', 'wcgpq'),
        'id' => 'wcgpq_mailing_service',
        'type' => 'text',
        'default' => 'smtp.gmail.com',
        'desc_tip' => true

    );

    $wcgpq_settings_array[] = array(
        'title' => __('Mailer Username', 'wcgpq'),
        'desc' => __('Enter the mailing service user name', 'wcgpq'),
        'id' => 'wcgpq_mailer_username',
        'type' => 'text',
        'default' => '',
        'desc_tip' => true
    );

    $wcgpq_settings_array[] = array(
        'title' => __('Mailer Password', 'wcgpq'),
        'desc' => __('Enter the 16 digit password issued for the app', 'wcgpq'),
        'id' => 'wcgpq_mailer_password',
        'type' => 'password',
        'default' => '',
        'desc_tip' => true
    );

    $wcgpq_settings_array[] = array(
        'title' => __('Mailer From', 'wcgpq'),
        'desc' => __('Enter the sender email', 'wcgpq'),
        'id' => 'wcgpq_sender_mail',
        'type' => 'text',
        'default' => '',
        'desc_tip' => true
    );

    $wcgpq_settings_array[] = array(
        'title' => __('Admin Email', 'wcgpq'),
        'desc' => __('Enter email here', 'wcgpq'),
        'id' => 'wcgpq_admin_email',
        'type' => 'text',
        'default' => '',
        'desc_tip' => true
    );

    $wcgpq_settings_array[] = array(
        'title' => __('Email Subject', 'wcgpq'),
        'id' => 'wcgpq_email_subject',
        'type' => 'text',
        'default' => 'Product Quotation',
        'desc_tip' => true,
        'description' => 'Subject for Product Quotation'
    );

    $wcgpq_settings_array[] = array(
        'title' => __('Email Template', 'wcgpq'),
        'id' => 'wcgpq_email_template',
        'type' => 'textarea',
        'css' => 'min-width:400px; min-height:150px',
        'default' => "New Quote Request Received\n\nA customer has requested a quote for their cart. Here are the details:\n\nCustomer Name: {name}\nCustomer Email: {email}\nPhone: {phone}\nCompany: {company}\n\nRequested Items:\n{cart_items}\n\nTotal Quantity: {total_quantity}\n\nCustomer Message:\n{message}\n\nYou can review and manage products from your admin dashboard using the links above.\n\nRegards,\n{store_name}",
        'desc_tip' => true,
        'description' => 'use placeholders like {name} ,{email}, {comments}'
    );

    $wcgpq_settings_array[] = array(
        'type' => 'sectionend',
        'id' => 'wcgpq_email_settings_title'
    );



    return $wcgpq_settings_array;
}

// Output the previews directly
function wcgpq_output_form_previews()
{
?>
    <style>
        .wcgpq-preview-section {
            margin: 20px 0;
            padding: 20px;
            background: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
    </style>

    <div class="wcgpq-preview-section">
        <h2>Form Preview</h2>
        <p>Below is how your selected form will look:</p>
        <?php echo wcgpq_generate_form_preview('form_a') ?>
        <?php echo wcgpq_generate_form_preview('form_b') ?>
    </div>
<?php
}

// Add settings tab
function wcgpq_add_settings_tab($tabs)
{
    $tabs['wcgpq_settings'] = __('Get a Quote', 'wcgpq');
    return $tabs;
}

add_filter('woocommerce_settings_tabs_array', 'wcgpq_add_settings_tab', 50);

// Display Settings content
function wcgpq_settings_tab_content()
{
    woocommerce_admin_fields(wcgpq_admin_settings());
}

add_action('woocommerce_settings_tabs_wcgpq_settings', 'wcgpq_settings_tab_content');

// Save Settings
function wcgpq_save_settings()
{
    woocommerce_update_options(wcgpq_admin_settings());

    // $locations = array(
    // 'product_list' => get_option('wcgpq_location_product_list', 'no'),
    // 'product_details' => get_option('wcgpq_location_product_details', 'no'),
    //     'cart' => get_option('wcgpq_location_cart', 'no')
    // );

    // update_option('wcgpq_locations', $locations);
}

add_action('woocommerce_update_options_wcgpq_settings', 'wcgpq_save_settings');


function wcgpq_admin_scripts($hook)
{

    if ($hook !== 'woocommerce_page_wc-settings') {
        error_log($hook . 'wcgpq_admin_scripts');

        return;
    }

    error_log('wcgpq_admin_scripts');

    wp_enqueue_script(
        'wcgpq-admin-js',
        plugin_dir_url(__FILE__) . '../assets/js/wcgpq_admin_settings.js',
        array(),
        '1.0',
        true
    );

    wp_enqueue_style(
        'wcgpq-admin-css',
        plugin_dir_url(__FILE__) . '../assets/css/wcgpq_admin_styles.css',
        array(),
        '1.0',
    );
}

add_action('admin_enqueue_scripts', 'wcgpq_admin_scripts');
