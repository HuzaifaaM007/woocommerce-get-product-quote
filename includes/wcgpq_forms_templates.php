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
    $forms   = wcgpq_get_default_forms();
    $choices = array();

    foreach ($forms as $id => $form) {
        $choices[$id] = $form['name'];
    }

    return $choices;
}

// Generate html preview of selected form
function wcgpq_generate_form_preview($form_id)
{
    $forms = wcgpq_get_default_forms();

    if (!isset($forms[$form_id])) {
        return "<p>Form not found </p>";
    }

    $form  =  $forms[$form_id];

    $html  =  '<div class="wcgpq-form-preview wcgpq-preview-' . $form_id . '">';
    $html .=  '<h3>' . $form['name'] . '</h3>';

    // Loop through fields
    foreach ($form['fields'] as $field) {
        $html      .=  '<p>';
        $html      .=  '<label>' . $field['label'] . '</label><br>';
        $input_line =  '<input type="' . $field['type'] . '" placeholder="' . $field['label'] . '" disabled>';
        // $html .= '<input type="' . $field['type'] . '" placeholder="' . $field['label'] . '" disabled>';
        error_log('input html' . $input_line);
        $html      .=  $input_line;
        $html      .=  '</p>';
    }

    $html          .=  '<br><button style="margin-top:10;" type="submit" class="button" id="wcgpq-form-button" disabled>Request Quote</button>';
    $html          .=  '</div>';

    return $html;
}
