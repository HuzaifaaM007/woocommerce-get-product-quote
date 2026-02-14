document.addEventListener('DOMContentLoaded', function () {

    console.log("WCGPQ Admin settings loaded");

    // Password toggle feature
    const password_field = document.getElementById('wcgpq_mailer_password');
    if (password_field) {
        const toggle_button = document.createElement('button');
        toggle_button.type = 'button';
        toggle_button.textContent = 'Show';
        toggle_button.className = 'button';
        toggle_button.style.marginLeft = '5px';

        password_field.insertAdjacentElement('afterend', toggle_button);

        toggle_button.addEventListener('click', function () {
            if (password_field.type === 'password') {
                password_field.type = 'text';
                toggle_button.textContent = 'Hide';
            } else {
                password_field.type = 'password';
                toggle_button.textContent = 'Show';
            }
        });
    }

    // Form preview toggle
    const form_select = document.getElementById('wcgpq_form_type');
    const previews = document.querySelectorAll('.wcgpq-form-preview');


    if (!form_select || previews.length === 0) {

        console.log("Form select not found or no previews available");
        console.log(previews.length);
        console.log(form_select);
        
        
        return;
    }

    console.log("Found", previews.length, "previews");

    function toggle_forms() {

        const selected = form_select.value;
        console.log('Selected form:', selected);

        const previews = document.querySelectorAll('.wcgpq-form-preview');
        console.log("Inside toggle_forms");
        console.log("previews length:", previews.length);
        console.log("previews:", previews);

        // Hide all previews
        previews.forEach(preview => {
            console.log("preview log", preview);

            // const parent_div = preview.parentElement;

            // removing as now  using tr 
            // if (parent_div) {
            //     parent_div.style.display = 'none';
            //     console.log('<<<<<<<<<<<<< parent div hidden >>>>>>>');

            // }
            // else{
            //     preview.style.display = 'none';
            //     console.log('<<<<<<<<<<<<< preview div hidden >>>>>>>');

            // }

            const row = preview.closest('tr');
            console.log(preview.closest('tr'));


            console.log('<<<<<<<<<<' + row + ">>>>>>>>>>>>>>>>>>>>>>>>>>>>>");

            if (row) {
                console.log('debug ' + row);
                console.log('debug >>> ' + selected);


                row.style.display = 'none';
            }
            else {
                console.log(' No tr found');
                const parent_element = preview.parentElement;

                if (parent_element) {
                    parent_element.style.display = 'none';
                }

            }
        });

        // Show selected preview
        const activepreview = document.querySelector('.wcgpq-preview-' + selected);

        console.log('Active preview element:', activepreview);

        if (activepreview) {

            // removed as now using tr tag
            // const parent_div = activepreview.parentElement;
            // if (parent_div) {
            //     parent_div.style.display = '';
            //     console.log('Showed parent div');

            // }
            // else {
            //     activepreview.style.display = 'block';
            //     console.log('showed previe block ');

            // }

            const row = activepreview.closest('tr');
            if (row) {
                console.log('using for tr');
                row.style.display = '';
            }
            else{
                const parent_element = activepreview.parentElement;
                if (parent_element) {
                    console.log('no tr found');
                    parent_element.style.display = '';
                    
                }
            }
        }
    }

    // Run on load
    toggle_forms();

    // Run on change
    form_select.addEventListener('change', toggle_forms);
});