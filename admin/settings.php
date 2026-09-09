<?php

/**
 * register plugin settings in Woo Commerce admin
 * 
 * Adds a setting section where admins can 
 * enable/disable woocommerce-get-product-qoute
 * 
 */


require_once plugin_dir_path(__FILE__) . '../includes/wcgpq_forms_templates.php';
require_once plugin_dir_path(__FILE__) . '../admin/wcgpq-form-builder.php';



add_action('woocommerce_admin_field_wcgpq_preview', 'wcgpq_render_preview_fields', 10);
// error_log("=== HOOK REGISTERED: woocommerce_admin_field_wcgpq_preview ===");

function wcgpq_render_preview_fields(array $value)
{
    error_log("value received " . print_r($value, true));

    $form_id = isset($value['form_id']) ? $value['form_id'] : 'form_a';
    $current_selection = get_option('wcgpq_form_type', 'form_a');
    $style = ($form_id !== $current_selection) ? 'style="display:none;"' : '';
    error_log('form_id : ' . $form_id);
?>
    <tr valign="top" <?php echo $style; ?> class="wcgpq-preview-row">
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
        'type'             =>  'sectionend',
        'id'                =>  'wcgpq_form_section_end'
    );

    $wcgpq_settings_array[] = array(
        'title'             =>  'Form Preview',
        'type'              =>  'title',
        'desc'              =>  'Below is how your selected form will look',
        'id'                =>  'wcgpq_form_preview_header',
        'desc_tip'          =>  true
    );

    $forms = get_option('wcgpq_forms', wcgpq_get_default_forms());

    if (!empty($forms) && is_array($forms)) {
        foreach ($forms as $form_id => $form_data) {
            $wcgpq_settings_array[] = array(
                'title'   => 'wcgpq_preview',
                'type'    => 'wcgpq_preview',
                'id'      => 'wcgpq_preview_' . $form_id,
                'form_id' => $form_id,
            );
        }
    }

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

function wcpgpq_render_forms_admin_page()
{
    $forms     =  get_option('wcgpq_forms', array());
    $editing   =  isset($_GET['edit'])                  ? sanitize_text_field($_GET['edit']) : null;
    $form_data =  ($editing && isset($forms[$editing])) ? $forms[$editing]                   : null;

?>
    <div class="wrap">
        <h1><?php echo $editing ? 'Edit Form' : 'Quote Forms'; ?></h1>

        <?php if (!$editing): ?>
            <table class="widefat">
                <thead>
                    <tr>
                        <th>Form ID</th>
                        <th>Name</th>
                        <th>Fields</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($forms as $form_id => $form): ?>
                        <tr>
                            <td><?php echo esc_html($form_id); ?></td>
                            <td><?php echo esc_html($form['name']); ?></td>
                            <td><?php echo count($form['fields']); ?></td>
                            <td>
                                <a href="<?php echo esc_url(admin_url('admin.php?page=wc-settings&tab=wcgpq_settings&section=forms&edit=' . $form_id)); ?>">Edit</a> |
                                <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin-post.php?action=wcgpq_delete_form&form_id=' . $form_id), 'wcgpq_delete_form')); ?>" onclick="return confirm('Delete this form?');">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <p><a href="<?php echo esc_url(admin_url('admin.php?page=wc-settings&tab=wcgpq_settings&section=forms&edit=new')); ?>" class="button button-primary">Add New Form</a></p>

        <?php else: ?>
            <?php wcgpq_render_form_editor($editing, $form_data); ?>
        <?php endif; ?>
    </div>
<?php
}

function wcgpq_render_form_editor(string $form_id, array $form_data)
{
    $is_new    =  ($form_id === 'new');
    $form_name =  $form_data ? $form_data['name']   : '';
    $fields    =  $form_data ? $form_data['fields'] : array();

?>
    <div id="wcgpq-form-editor-wrap">
        <input type="hidden" id="wcgpq-form-id" value="<?php echo $is_new ? '' : esc_attr($form_id); ?>">
        <?php wp_nonce_field('wcgpq_save_form_nonce', 'wcgpq_nonce_field'); ?>

        <table class="form-table">
            <tr>
                <th><label>Form Name</label></th>
                <td><input type="text" id="wcgpq-form-name" value="<?php echo esc_attr($form_name); ?>" class="regular-text" required></td>
            </tr>
            <?php if ($is_new): ?>
                <tr>
                    <th><label>Form ID (slug)</label></th>
                    <td><input type="text" id="wcgpq-form-slug" class="regular-text" pattern="[a-z0-9_]+" required placeholder="e.g. form_b"></td>
                </tr>
            <?php endif; ?>
        </table>

        <h2>Fields</h2>
        <table class="widefat" id="wcgpq-fields-table">
            <thead>
                <tr>
                    <th>Field ID</th>
                    <th>Label</th>
                    <th>Type</th>
                    <th>Required</th>
                    <th>Placeholder</th>
                    <th>Options</th>
                    <th></th>
                </tr>
            </thead>
            <tbody id="wcgpq-fields-body">
                <?php foreach ($fields as $i => $field): ?>
                    <?php echo wcgpq_render_field_row($i, $field); ?>
                <?php endforeach; ?>
            </tbody>
        </table>

        <p><button type="button" id="wcgpq-add-field" class="button">+ Add Field</button></p>
        <p>
            <button type="button" id="wcgpq-save-form-btn" class="button button-primary">Save Form</button>
            <span id="wcgpq-save-status" style="margin-left:10px;"></span>
        </p>
    </div>

    <script id="wcgpq-field-row-template" type="text/template">
        <?php echo wcgpq_render_field_row('__INDEX__', array()); ?>
</script>

    <script>
        (function() {
            var body = document.getElementById('wcgpq-fields-body');
            var addBtn = document.getElementById('wcgpq-add-field');
            var template = document.getElementById('wcgpq-field-row-template').innerHTML;
            var counter = <?php echo count($fields); ?>;

            addBtn.addEventListener('click', function() {
                var row = template.replace(/__INDEX__/g, counter);
                body.insertAdjacentHTML('beforeend', row);
                counter++;
            });

            body.addEventListener('click', function(e) {
                if (e.target.classList.contains('wcgpq-remove-field')) {
                    e.target.closest('tr').remove();
                }
            });

            document.getElementById('wcgpq-save-form-btn').addEventListener('click', function() {
                var statusEl = document.getElementById('wcgpq-save-status');
                statusEl.textContent = 'Saving...';

                var data = new FormData();
                data.append('action', 'wcgpq_save_form');
                data.append('_wpnonce', document.getElementById('wcgpq_nonce_field').value);
                data.append('form_id', document.getElementById('wcgpq-form-id').value);
                data.append('form_name', document.getElementById('wcgpq-form-name').value);

                var slugField = document.getElementById('wcgpq-form-slug');
                if (slugField) {
                    data.append('form_slug', slugField.value);
                }

                var rows = body.querySelectorAll('tr');
                rows.forEach(function(row, i) {
                    data.append('fields[' + i + '][id]', row.querySelector('[name$="[id]"]').value);
                    data.append('fields[' + i + '][label]', row.querySelector('[name$="[label]"]').value);
                    data.append('fields[' + i + '][type]', row.querySelector('[name$="[type]"]').value);
                    var reqBox = row.querySelector('[name$="[required]"]');
                    if (reqBox && reqBox.checked) {
                        data.append('fields[' + i + '][required]', '1');
                    }
                    data.append('fields[' + i + '][placeholder]', row.querySelector('[name$="[placeholder]"]').value);
                    data.append('fields[' + i + '][options]', row.querySelector('[name$="[options]"]').value);
                });

                fetch('<?php echo esc_url_raw(admin_url('admin-post.php')); ?>', {
                    method: 'POST',
                    body: data,
                    credentials: 'same-origin'
                }).then(function(response) {
                    if (response.redirected) {
                        window.location.href = response.url;
                    } else {
                        statusEl.textContent = 'Saved.';
                    }
                }).catch(function(err) {
                    statusEl.textContent = 'Error saving form.';
                    console.error(err);
                });
            });

            document.addEventListener('change', function(e) {
                // Check if the changed element is a field type select dropdown
                if (e.target && e.target.matches('select[name$="[type]"]')) {
                    const select =  e.target;
                    const row    =  select.closest('tr');
                    if (!row) return;

                    const selectedType =  select.value;
                    const optionsCell  =  row.querySelector('.wcgpq-options-cell');

                    if (!optionsCell) return;

                    // Field types that require options
                    const typesWithOptions = ['radio', 'select', 'checkbox'];

                    if (typesWithOptions.includes(selectedType)) {
                        // Get existing value if restoring from disabled state
                        const currentInput =  optionsCell.querySelector('input');
                        const previousVal  =  currentInput ? currentInput.dataset.previousValue || '' : '';

                        optionsCell.innerHTML = `
                <input type="text" 
                       name="fields[${getRowIndex(row)}][options]" 
                       value="${previousVal}" 
                       placeholder="Comma-separated values">
            `;
                    } else {
                        // Store value temporarily in dataset before disabling, in case user switches back
                        const currentInput =  optionsCell.querySelector('input');
                        const savedValue   =  currentInput ? currentInput.value : '';

                        optionsCell.innerHTML = `
                <input type="text" 
                       disabled 
                       value="disabled" 
                       data-previous-value="${savedValue}">
            `;
                    }
                }
            });

            // Helper function to extract row index from existing input names
            function getRowIndex(row) {
                const input = row.querySelector('[name^="fields["]');
                if (input) {
                    const  match =  input.name.match(/fields\[(\d+)\]/);
                    return match ?  match[1] : 0;
                }
                return 0;
            }
        })();
    </script>
<?php
}

function wcgpq_render_field_row(int|string $index,array $field)
{
    $id          =  isset($field['id'])          ? $field['id']          : '';
    $label       =  isset($field['label'])       ? $field['label']       : '';
    $type        =  isset($field['type'])        ? $field['type']        : 'text';
    $required    = !empty($field['required']);
    $placeholder =  isset($field['placeholder']) ? $field['placeholder'] : '';
    $options     =  isset($field['options'])     ? $field['options']     : array();

    error_log('printing the options for radio :' . print_r($options, true));
    if (isset($options)) {
        $options_val = is_array($options) ? implode(', ', $options) : $options;
        error_log('printing the option vlaue in string form : ' . $options_val);
    }
    ob_start();
?>
    <tr>
        <td><input type="text" name="fields[<?php echo $index; ?>][id]" value="<?php echo esc_attr($id); ?>" required></td>
        <td><input type="text" name="fields[<?php echo $index; ?>][label]" value="<?php echo esc_attr($label); ?>" required></td>
        <td>
            <select name="fields[<?php echo $index; ?>][type]">
                <option value="text" <?php selected($type, 'text');              ?>>Text</option>
                <option value="email" <?php selected($type, 'email');           ?>>Email</option>
                <option value="textarea" <?php selected($type, 'textarea');  ?>>Textarea</option>
                <option value="number" <?php selected($type, 'number');        ?>>Number</option>
                <option value="checkbox" <?php selected($type, 'checkbox');  ?>>Checkbox</option>
                <option value="radio" <?php selected($type, 'radio');           ?>>Radio</option>
                <option value="file" <?php selected($type, 'file');              ?>>File</option>
                <option value="range" <?php selected($type, 'range');           ?>>Range</option>
                <option value="tel" <?php selected($type, 'tel');                 ?>>Tel</option>
                <option value="url" <?php selected($type, 'url');                 ?>>Url</option>
                <option value="password" <?php selected($type, 'password');  ?>>Password</option>
                <option value="date" <?php selected($type, 'date');              ?>>Date</option>
                <option value="time" <?php selected($type, 'time');              ?>>Time</option>
                <option value="color" <?php selected($type, 'color');           ?>>Color</option>

            </select>
        </td>

        <td><input type="checkbox" name="fields[<?php echo $index; ?>][required]" value="1" <?php checked($required); ?>></td>
        <td><input type="text" name="fields[<?php echo $index; ?>][placeholder]" value="<?php echo esc_attr($placeholder); ?>"></td>
        <td class="wcgpq-options-cell">
            <?php if ($type === 'radio' || $type === 'select' || $type === 'checkbox'): ?>
                <input type="text"
                    name="fields[<?php echo $index; ?>][options]"
                    value="<?php echo esc_attr($options_val); ?>"
                    placeholder="Comma-separated values">
            <?php else: ?>
                <input type="text" disabled value="disabled">
            <?php endif; ?>
        </td>
        <td><button type="button" class="button wcgpq-remove-field">Remove</button></td>
    </tr>
<?php
    return ob_get_clean();
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
        <?php
        $selected_form = get_option('wcgpq_form_type');
        echo wcgpq_generate_form_preview($selected_form) ?>
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
?>
    <div class="wcgpq-settings-header" style="background:#fff; padding: 15px; border:1px solid #ccc; border-left:4px solid #25D366; margin: bottom 20px; margin-bottom:20px;">
        <h2 style="margin:0 0 5px 0; "><?php _e('Get Product Quote', 'wcgpq'); ?></h2>
        <p style="margin:0;"><?php _e('Need assistance? Check out our <a href="#" target="_blank">Documentation</a> or submit a <a href="#" target="_blank">Support Ticket</a>.', 'wcgpq'); ?></p>
    </div>
<?php

    $current_section =  isset($_GET['section']) ? sanitize_text_field(wp_unslash($_GET['section'])) : 'general';
    $sections        =  array(
        'general'    =>  __('General Settings', 'wcgpq'),
        'forms'      =>  __('Form Settings', 'wcgpq'),
    );
    echo '<div class="wcgpq-subnav-container">';
    echo '<ul class="subsubsub">';

    foreach ($sections as $id => $label) {
        $url        = admin_url('admin.php?page=wc-settings&tab=wcgpq_settings&section=' . urlencode($id));
        $is_current = ($current_section === $id);
        $class_attr = $is_current ? ' class="current"' : '';
        $aria_attr  = $is_current ? ' aria-current="page"' : '';

        echo '<li><a href="' . esc_url($url) . '"' . $class_attr . $aria_attr . '>' . esc_html($label) . '</a></li>';
    }

    echo '</ul>';
    echo '</div>';

    switch ($current_section) {
        case 'forms':
            echo '<style>.woocommerce-save-button { display: none; }</style>';
            echo '<h3>' . esc_html__('Forms Settings', 'wcgpq') . '</h3>';
            wcpgpq_render_forms_admin_page();
            break;
        case 'general':
        default:
            echo '<h3>' . esc_html__('General Settings', 'wcgpq') . '</h3>';
            woocommerce_admin_fields(wcgpq_admin_settings());
            break;
    }
}

add_action('woocommerce_settings_tabs_wcgpq_settings', 'wcgpq_settings_tab_content');

// Save Settings
function wcgpq_save_settings()
{
    $current_section = isset($_GET['section']) ? sanitize_text_field(wp_unslash($_GET['section'])) : 'general';

    if ($current_section === 'forms') {
        return;
    }

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
