<?php

/**
 * register plugin settings in Woo Commerce admin
 * 
 * Adds a setting section where admins can 
 * enable/disable woocommerce-get-product-qoute
 * 
 */

// add setting fields
function wcgpq_admin_settings()
{

    // settings code
    $wcgpq_settings_array = array(
        array(
            'title' => __('WooCommerce Get Product Quote', 'wcgpq'),
            'type' => 'title',
            'desc' => __('Adds the gets qoute option on products ', 'wcgpq'),
            'id' => 'wcgpq_settings_title'
        ),
        array(
            'title' => __('Admin Email', 'wcgpq'),
            'desc' => __('Enter email here ', 'wcgpq'),
            'id' => 'wcgpq_admin_email',
            'type' => 'text',
            'default' => '',
            'desc_tip' => true
        ),
        array(
            'title' => __('Email Subject', 'wcgpq'),
            'id' => 'wcgpq_email_subject',
            'type' => 'text',
            'deafult' => 'Product Quoatation',
            'desc_tip' => true,
            'description' => 'Subject for Product Quotation'
        ),
        array(
            'title' => __('Email Template', 'wcgpq'),
            'id' => 'wcgpq_email_template',
            'type' => 'textarea',
            'css' => 'min-width:400px; min-height:150px',
            'default' => "",
            'desc_tip' => true,
            'description' => 'use placeholders like {name} ,{email}, {comments}'
        ),
        array(
            'type' => 'sectioned',
            'id' => 'wcgpq_email_settings_title'
        )

    );

    return add_filter('wcgpq_admin_settings', $wcgpq_settings_array);
}

// Add settings tab
function wcgpq_add_settings_tab($tabs)
{
    $tabs['wcgpq_settings'] = __('Product Quotation', 'wcgpq');
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

add_action('woocommerce_update_options_wcgpq_settings','wcgpq_save_settings');


