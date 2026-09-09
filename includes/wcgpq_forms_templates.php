<?php


function wcgpq_get_default_forms()
{

    return array(

        // Form-A
        'form_a' => array(
            'name'   =>  'Basic Contact Form',
            'fields' =>  array(
                array(
                    'id'    =>  'user_name',
                    'label' =>  'Your Name',
                    'type'  =>  'text',
                    'required' => true,
                    'placeholder' => 'name',
                    'always_on' => true,
                ),
                array(
                    'id'    =>  'form_a_email',
                    'label' =>  'Email Address',
                    'type'  =>  'email',
                    'required' => true,
                    'placeholder' => 'email',
                    'always_on' => true,
                ),
                array(
                    'id'    =>  'form_a_phone_number',
                    'label' =>  'Phone No',
                    'type'  =>  'text',
                    'required' => true,
                    'placeholder' => 'phone number',
                    'always_on' => true,

                ),
                array(
                    'id'    =>  'form_a_message',
                    'label' =>  'Message',
                    'type'  =>  'textarea',
                    'required' => true,
                    'placeholder' => 'message',
                    'always_on' => true,
                )
            )
        ),
        'form_b' => array(
            'name'   =>  'Detailed Quote Form',
            'fields' =>  array(
                array(
                    'id'    =>  'fullname',
                    'label' =>  'Full Name',
                    'type'  =>  'text',
                    'required' => true,
                    'placeholder' => 'fullname',
                    'always_on' => true,
                ),
                array(
                    'id'    =>  'company',
                    'label' =>  'Company Name',
                    'type'  =>  'text',
                    'placeholder' => 'company',
                    'always_on' => true,
                ),
                array(
                    'id'    =>  'email',
                    'label' =>  'Business Email',
                    'type'  =>  'email',
                    'required' => true,
                    'placeholder' => 'bussiness Email',
                    'always_on' => true,
                ),
                array(
                    'id'    =>  'quantity',
                    'label' =>  'Quantity Needed',
                    'type'  =>  'number',
                    'required' => true,
                    'placeholder' => '500',
                    'always_on' => true,
                ),
                array(
                    'id'    =>  'form_b_message',
                    'label' =>  'Message',
                    'type'  =>  'textarea',
                    'required' => true,
                    'placeholder' => 'message',
                    'always_on' => true,
                ),

            )
        ),

    );
}

// get form Choices
function wcgpq_get_form_choices()
{
    $forms   = get_option('wcgpq_forms', array());
    $choices = array();

    foreach ($forms as $id => $form) {
        $choices[$id] = $form['name'];
    }

    return $choices;
}

// Generate html preview of selected form
function wcgpq_generate_form_preview(string $form_id)
{
    $default_forms = wcgpq_get_default_forms();
    $forms         = get_option('wcgpq_forms', $default_forms); // Fallback to defaults if DB option is missing

    // Check if form ID exists
    if (!isset($forms[$form_id])) {
        return "<p>Form not found</p>";
    }

    $form = $forms[$form_id];

    $html  = '<div class="wcgpq-form-preview wcgpq-preview-' . esc_attr($form_id) . '">';
    $html .= '<h3>' . esc_html($form['name'] ?? 'Form Preview') . '</h3>';

    // Loop through form fields
    if (!empty($form['fields']) && is_array($form['fields'])) {
        foreach ($form['fields'] as $field) {
            $label       = $field['label'] ?? '';
            $type        = $field['type'] ?? 'text';
            // Use explicitly defined placeholder, or fall back to the label if empty
            $placeholder = !empty($field['placeholder']) ? $field['placeholder'] : $label;

            $html .= '<p>';
            $html .= '<label>' . esc_html($label) . '</label><br>';

            switch ($type) {
                case 'textarea':
                    $html .= '<textarea placeholder="' . esc_attr($placeholder) . '" disabled class="regular-text" rows="4"></textarea>';
                    break;

                case 'checkbox':
                    $html .= '<label><input type="checkbox" disabled /> ' . esc_html($placeholder ? $placeholder : 'Checkbox Option') . '</label>';
                    break;

                case 'radio':
                    $html .= '<label style="margin-right: 10px;"><input type="radio" disabled checked /> ' . esc_html($placeholder ? $placeholder : 'Radio Option 1') . '</label>';
                    $html .= '<label><input type="radio" disabled /> Option 2</label>';
                    break;

                case 'file':
                    $html .= '<input type="file" disabled />';
                    break;

                case 'range':
                    $html .= '<input type="range" disabled />';
                    break;

                case 'color':
                    $html .= '<input type="color" value="#2271b1" disabled />';
                    break;

                case 'date':
                case 'time':
                case 'number':
                case 'tel':
                case 'url':
                case 'password':
                case 'email':
                case 'text':
                default:
                    $html .= '<input type="' . esc_attr($type) . '" placeholder="' . esc_attr($placeholder) . '" disabled class="regular-text" />';
                    break;
            }

            $html .= '</p>';
        }
    }

    $html .= '<br><button style="margin-top:10px;" type="submit" class="button" id="wcgpq-form-button" disabled>Request Quote</button>';
    $html .= '</div>';

    error_log('printing the html 151' . $html);
    return $html;
}
