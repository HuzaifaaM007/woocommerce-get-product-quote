document.addEventListener('DOMContentLoaded', function () {

    console.log("WCGPQ Admin settings loaded");

    // -------------------------------------------------------------
    // 1. Password Visibility Toggle
    // -------------------------------------------------------------
    const passwordField = document.getElementById('wcgpq_mailer_password');

    if (passwordField) {
        const toggleButton = document.createElement('button');
        toggleButton.type = 'button';
        toggleButton.textContent = 'Show';
        toggleButton.className = 'button';
        toggleButton.style.marginLeft = '5px';

        passwordField.insertAdjacentElement('afterend', toggleButton);

        toggleButton.addEventListener('click', function () {
            const isPassword = passwordField.type === 'password';
            passwordField.type = isPassword ? 'text' : 'password';
            toggleButton.textContent = isPassword ? 'Hide' : 'Show';
        });
    }

    // -------------------------------------------------------------
    // 2. Form Preview Dynamic Toggle
    // -------------------------------------------------------------
    const formSelect = document.getElementById('wcgpq_form_type');

    if (!formSelect) {
        console.warn("WCGPQ: Select dropdown (#wcgpq_form_type) not found in DOM.");
        return;
    }

    function toggleForms() {
        const selected = formSelect.value;
        const previews = document.querySelectorAll('.wcgpq-form-preview');

        if (previews.length === 0) {
            console.warn("WCGPQ: No form previews (.wcgpq-form-preview) found on page.");
            return;
        }

        // Hide all previews (checks for <tr> first, then parent container, then self)
        previews.forEach(preview => {
            const targetElement = preview.closest('tr') || preview.parentElement || preview;
            targetElement.style.display = 'none';
        });

        // Show the selected form preview
        const activePreview = document.querySelector('.wcgpq-preview-' + selected);

        if (activePreview) {
            const targetElement = activePreview.closest('tr') || activePreview.parentElement || activePreview;
            targetElement.style.display = '';
        } else {
            console.warn("WCGPQ: Active preview element not found for option:", selected);
        }
    }

    // Initial check on load
    toggleForms();

    // Listen for select dropdown changes
    formSelect.addEventListener('change', toggleForms);
});