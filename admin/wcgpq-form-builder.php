<?php

if (!defined('ABSPATH')) {
    exit();
}


require_once plugin_dir_path(__FILE__) . '../includes/wcgpq_forms_templates.php';





function wcgpq_save_forms(array $form_data)
{
    $form_option = 'wcgpq_forms';
    $default_forms = wcgpq_get_default_forms();

    $wcgpq_forms = get_option($form_option, $default_forms);

    if (empty($wcgpq_forms)) {
        return;
    }

    foreach ($form_data as $form => $form_details) {

        if (array_key_exists($form, $wcgpq_forms)) {
            return;
        }

        $wcgpq_forms[$form] = $form_details;

        update_option($form_option, $wcgpq_forms);
    }
}

function wcgpq_render_forms(string $form_id)
{
    $wcgpq_forms = get_option('wcgpq_forms');

    if (!isset($wcgpq_forms[$form_id])) {
        return "<p>Form not found </p>";
    }

    $form = $wcgpq_forms[$form_id];

    $form_html = '<p>';
    foreach ($form['fields'] as $field) {
        $form_html .= wcgpq_render_fields($field);
    }

    return $form_html;
}

/**
 * Render a single form field HTML string based on a single field configuration array.
 *
 * @param array $field Single field schema array.
 * @return string Rendered HTML string for the field.
 */
function wcgpq_render_fields(array $field): string
{
    $id          = esc_attr($field['id'] ?? '');
    $label       = esc_html($field['label'] ?? '');
    $type        = esc_attr($field['type'] ?? 'text');
    $is_required = ! empty($field['required']) ? 'required' : '';
    $req_mark    = ! empty($field['required']) ? ' <span class="required">*</span>' : '';
    $default     = '';

    // Handle repopulation with $_POST or field default fallback
    $raw_value = isset($_POST[$id]) ? wp_unslash($_POST[$id]) : $default;

    // 1. Hidden inputs don't need a label or wrapper div
    if ('hidden' === $type) {
        return sprintf(
            '<input type="hidden" id="%s" name="%s" value="%s" />',
            $id,
            $id,
            esc_attr(is_array($raw_value) ? '' : $raw_value)
        );
    }

    $output = '';

    // 2. Omit top-level label for checkboxes (label is rendered inline)
    if ('checkbox' !== $type) {
        $output .= '<label for="' . $id . '">' . $label . $req_mark . '</label><br>';
    }

    switch ($type) {

        // --- Textarea ---
        case 'textarea':
            $rows    = intval($field['rows'] ?? 5);
            $output .= sprintf(
                '<textarea id="%s" name="%s" %s class="regular-text" rows="%d">%s</textarea>',
                $id,
                $id,
                $is_required,
                $rows,
                esc_textarea(is_array($raw_value) ? '' : $raw_value)
            );
            return $output;

            // --- Select Dropdown ---
        case 'select':
            $options = (array) ($field['options'] ?? array());
            $output .= sprintf('<select id="%s" name="%s" %s class="regular-text">', $id, $id, $is_required);

            if (isset($field['placeholder'])) {
                $output .= '<option value="">' . esc_html($field['placeholder']) . '</option>';
            }

            foreach ($options as $val => $opt_label) {
                $selected = selected($raw_value, $val, false);
                $output  .= sprintf('<option value="%s" %s>%s</option>', esc_attr($val), $selected, esc_html($opt_label));
            }

            $output .= '</select>';
            return $output;

            // --- Single Checkbox ---
        case 'checkbox':
            $checked = checked(! empty($raw_value), true, false);
            $output .= sprintf(
                '<label><input type="checkbox" id="%s" name="%s" value="1" %s %s /> %s%s</label>',
                $id,
                $id,
                $checked,
                $is_required,
                $label,
                $req_mark
            );
            return $output;

            // --- Radio Buttons ---
        case 'radio':
            $options = (array) ($field['options'] ?? array());
            foreach ($options as $val => $opt_label) {
                $checked = checked($raw_value, $val, false);
                $opt_id  = $id . '_' . sanitize_key($val);
                $output .= sprintf(
                    '<label style="margin-right: 12px;"><input type="radio" id="%s" name="%s" value="%s" %s %s /> %s</label>',
                    esc_attr($opt_id),
                    $id,
                    esc_attr($val),
                    $checked,
                    $is_required,
                    esc_html($opt_label)
                );
            }
            return $output;

            // --- File Upload ---
        case 'file':
            $accept  = isset($field['accept']) ? 'accept="' . esc_attr($field['accept']) . '"' : '';
            $output .= sprintf(
                '<input type="file" id="%s" name="%s" %s %s />',
                $id,
                $id,
                $accept,
                $is_required
            );
            return $output;

            // --- Number & Range Inputs ---
        case 'number':
        case 'range':
            $min   = isset($field['min']) ? 'min="' . esc_attr($field['min']) . '"' : '';
            $max   = isset($field['max']) ? 'max="' . esc_attr($field['max']) . '"' : '';
            $step  = isset($field['step']) ? 'step="' . esc_attr($field['step']) . '"' : '';
            $class = ('number' === $type) ? 'class="regular-text"' : '';

            $output .= sprintf(
                '<input type="%s" id="%s" name="%s" value="%s" %s %s %s %s %s />',
                $type,
                $id,
                $id,
                esc_attr(is_array($raw_value) ? '' : $raw_value),
                $min,
                $max,
                $step,
                $is_required,
                $class
            );
            return $output;

            // --- Standard HTML5 Inputs (text, email, tel, url, password, date, time, color, etc.) ---
        case 'email':
        case 'text':
        case 'tel':
        case 'url':
        case 'password':
        case 'date':
        case 'time':
        case 'color':
        default:
            $placeholder = isset($field['placeholder']) ? 'placeholder="' . esc_attr($field['placeholder']) . '"' : '';
            $output     .= sprintf(
                '<input type="%s" id="%s" name="%s" value="%s" %s %s class="regular-text" />',
                $type,
                $id,
                $id,
                esc_attr(is_array($raw_value) ? '' : $raw_value),
                $placeholder,
                $is_required
            );
            return $output;
    }
}
