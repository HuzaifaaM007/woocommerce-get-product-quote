<?php


function wcgpq_get_all_forms()
{

    return array(

        // Form-A
        'form_a' => array(
            'name' => 'Basic Contact Form',
            'fields' => array(
                array(
                    'id' => 'form_a_user_name',
                    'label' => 'Your Name',
                    'type' => 'text'
                ),
                array(
                    'id' => 'form_a_email',
                    'label' => 'Email Address',
                    'type' => 'email'
                ),
                array(
                    'id' => 'form_a_phone_number',
                    'label' => 'Phone No',
                    'type' => 'text'
                ),
                array(
                    'id' => 'form_a_message',
                    'label' => 'Message',
                    'type' => 'textarea'
                )
            )
        ),
        'form_b' => array(
            'name' => 'Detailed Quote Form',
            'fields' => array(
                array(
                    'id' => 'fullname',
                    'label' => 'Full Name',
                    'type' => 'text'
                ),
                array(
                    'id' => 'company',
                    'label' => 'Company Name',
                    'type' => 'text'
                ),
                array(
                    'id' => 'email',
                    'label' => 'Business Email',
                    'type' => 'email'
                ),
                array(
                    'id' => 'quantity',
                    'label' => 'Quantity Needed',
                    'type' => 'number'
                ),
                array(
                    'id' => 'form_b_message',
                    'label' => 'Message',
                    'type' => 'textarea'
                ),

            )
        ),

    );
}

// get form Choices
function wcgpq_get_form_choices()
{
    $forms = wcgpq_get_all_forms();
    $choices = array();

    foreach ($forms as $id => $form) {
        $choices[$id] = $form['name'];
    }

    return $choices;
}

// Generate html preview of selected form
function wcgpq_generate_form_preview($form_id)
{
    $forms = wcgpq_get_all_forms();

    if (!isset($forms[$form_id])) {
        return "<p>Form not found </p>";
    }

    $form = $forms[$form_id];

    $html = '<div class="wcgpq-form-preview wcgpq-preview-' . $form_id . '">';
    $html .= '<h3>' . $form['name'] . '</h3>';

    // Loop through fields
    foreach ($form['fields'] as $field) {
        $html .= '<p>';
        $html .= '<label>' . $field['label'] . '</label><br>';
        $input_line = '<input type="' . $field['type'] . '" placeholder="' . $field['label'] . '" disabled>';
        // $html .= '<input type="' . $field['type'] . '" placeholder="' . $field['label'] . '" disabled>';
        error_log('input html' . $input_line);
        $html .= $input_line;
        $html .= '</p>';
    }

    $html .= '<br><button style="margin-top:10;" type="submit" class="button" id="wcgpq-form-button" disabled>Request Quote</button>';
    $html .= '</div>';

    return $html;
}
