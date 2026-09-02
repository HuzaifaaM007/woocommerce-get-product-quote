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

    $config = require_once plugin_dir_path(__FILE__) . '../includes/wcgpq_config.php';
    // settings code
    $wcgpq_settings_array   =  array();

    $wcgpq_settings_array[] =  array(
        'title'             =>  __('WooCommerce Get Product Quote', 'wcgpq'),
        'type'              =>  'title',
        'desc'              =>  __('Adds the gets qoute option on products ', 'wcgpq'),
        'id'                =>  'wcgpq_settings_title'
    );

    $wcgpq_settings_array[] =   array(
        'title'             =>  __('Get product quote cart page', 'wcgpq'),
        'desc'              =>  __('Add get product quote in shop page for each product', 'wcgpq'),
        'id'                =>  'wcgpq_location_product_list',
        'type'              =>  'checkbox',
        'default'           =>  'no'

    );

    $wcgpq_settings_array[] =   array(
        'title'             =>  __('Get product quote cart page', 'wcgpq'),
        'desc'              =>  __('Add get product quote in product details page', 'wcgpq'),
        'id'                =>  'wcgpq_location_product_details',
        'type'              =>  'checkbox',
        'default'           =>  'no'

    );

    $wcgpq_settings_array[] =   array(
        'title'             =>  __('Get product quote cart page', 'wcgpq'),
        'desc'              =>  __('Add get product quote in listing page for all products in cart', 'wcgpq'),
        'id'                =>  'wcgpq_location_cart',
        'type'              =>  'checkbox',
        'default'           =>  'no'

    );

    $wcgpq_settings_array[] = array(
        'title'             =>  __('Get product quote form', 'wcgpq'),
        'desc'              =>  __('Select the type of the form to display for getting product quote', 'wcgpq'),
        'id'                =>  'wcgpq_form_type',
        'type'              =>  'select',
        'options'           =>  wcgpq_get_form_choices(),
        'default'           =>  'form_a',
        'desc_tip'          =>  true,

    );

    $wcgpq_settings_array[] = array(
        'title'             =>  'sectionend',
        'id'                =>  'wcgpq_form_section_end'
    );

    $wcgpq_settings_array[] = array(
        'title'             =>  'Form Preview',
        'type'              =>  'title',
        'desc'              =>  'Below is how your selected form will look',
        'id'                =>  'wcgpq_form_preview_header',
        'desc_tip'          =>  true
    );

    $wcgpq_settings_array[] = array(
        'title'             =>  'wcgpq_preview',
        'id'                =>  'wcgpq_preview',
        'form_id'           =>  'form_a'

    );

    $wcgpq_settings_array[] = array(
        'title'             =>  'wcgpq_preview',
        'id'                =>  'wcgpq_preview',
        'form_id'           =>  'form_b'
    );

    $wcgpq_settings_array[] = array(
        'title'             =>  __('Mailing Host', 'wcgpq'),
        'desc'              =>  __('Enter the hosting service', 'wcgpq'),
        'id'                =>  'wcgpq_mailing_service',
        'type'              =>  'text',
        'default'           => 'smtp.gmail.com',
        'desc_tip'          => true

    );

    $wcgpq_settings_array[] = array(
        'title'             =>  __('Mailer Username', 'wcgpq'),
        'desc'              =>  __('Enter the mailing service user name', 'wcgpq'),
        'id'                =>  'wcgpq_mailer_username',
        'type'              =>  'text',
        'default'           =>  '',
        'desc_tip'          =>  true
    );

    $wcgpq_settings_array[] = array(
        'title'             =>  __('Mailer Password', 'wcgpq'),
        'desc'              =>  __('Enter the 16 digit password issued for the app', 'wcgpq'),
        'id'                =>  'wcgpq_mailer_password',
        'type'              =>  'password',
        'default'           =>  '',
        'desc_tip'          =>  true
    );

    $wcgpq_settings_array[] = array(
        'title'             =>  __('Mailer From', 'wcgpq'),
        'desc'              =>  __('Enter the sender email', 'wcgpq'),
        'id'                =>  'wcgpq_sender_mail',
        'type'              =>  'text',
        'default'           =>  '',
        'desc_tip'          =>  true
    );

    $wcgpq_settings_array[] = array(
        'title'             =>  __('Admin Email', 'wcgpq'),
        'desc'              =>  __('Enter email here', 'wcgpq'),
        'id'                =>  'wcgpq_admin_email',
        'type'              =>  'text',
        'default'           =>  '',
        'desc_tip'          =>  true
    );

    $wcgpq_settings_array[] = array(
        'title'             =>  __('Email Subject', 'wcgpq'),
        'id'                =>  'wcgpq_email_subject',
        'type'              =>  'text',
        'default'           =>  'Product Quotation',
        'desc_tip'          =>  true,
        'description'       =>  'Subject for Product Quotation'
    );

    $wcgpq_settings_array[] = array(
        'title'             =>  __('Email Template for Quote from Cart', 'wcgpq'),
        'id'                =>  'wcgpq_email_template_for_cart_quote',
        'type'              =>  'textarea',
        'css'               =>  'min-width: 600px; height: 300px; font-family: monospace;',
        'default'           =>  $config['default_cart_template'],
        'sanitize_callback' => 'wp_kses_post',
        'desc_tip'          => true,
        'description'       => 'use placeholders like {name} ,{email}, {comments}'
    );

    $wcgpq_settings_array[] = array(
        'title'             =>  __('Email Template for single Product Quote', 'wcgpq'),
        'id'                =>  'wcgpq_email_template_for_product_quote',
        'type'              =>  'textarea',
        'css'               =>  'min-width: 600px; height: 300px; font-family: monospace;',
        'default'           =>  $config['default_product_template'],
        'sanitize_callback' =>  'wp_kses_post',
        'desc_tip'          =>  true,
        'description'       =>  'use placeholders like {name} ,{email}, {comments}'
    );

    $wcgpq_settings_array[] = array(
        'type'              => 'sectionend',
        'id'                => 'wcgpq_email_settings_title'
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
}

add_action('woocommerce_update_options_wcgpq_settings', 'wcgpq_save_settings');

function wcgpq_admin_scripts(string $hook)
{

    if ($hook !== 'woocommerce_page_wc-settings') {
        error_log($hook . 'wcgpq_admin_scripts');

        return;
    }

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
