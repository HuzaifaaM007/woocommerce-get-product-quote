// Product Quote Button js

document.addEventListener('DOMContentLoaded', function () {

    // const product_quote_buttons = document.querySelectorAll('.wcgpq-button');
    const cart_quote_button = document.querySelector('.wcgpq-cart');
    const product_quote_button = document.querySelector('.wcgpq-product');



    const popup_overlay = document.getElementById('wcgpq-popup-overlay');
    const popup_close = document.getElementById('wcgpq-close-popup');
    const quote_form = document.getElementById('wcgpq-form');
    const form_response = document.getElementById('wcgpq-form-response');
    const cart_count_input = document.getElementById('wcgpq-cart-count');
    const product_id_input = document.getElementById('wcgpq-product-id');

    initializeEventListeners();

    /**
     * Initialize all event listeners
     */
    function initializeEventListeners() {
        // Product quote buttons
        // if (product_quote_buttons.length) {
        //     product_quote_buttons.forEach(button => {
        //         button.addEventListener('click', handleProductButtonClick);
        //     });
        // } else {
        //     console.log('Product-quote-button not found!');
        // }

        // Cart quote button
        if (cart_quote_button) {
            console.log("cart_quote_button pressed");

            cart_quote_button.addEventListener('click', handleCartButtonClick);
        }

        if (product_quote_button) {
            console.log('product quote button');

            product_quote_button.addEventListener('click', handleProductButtonClick);
        }

        // Popup close button
        if (popup_close) {
            popup_close.addEventListener('click', closePopup);
        }

        // Popup overlay click (close on outside click)
        if (popup_overlay) {
            popup_overlay.addEventListener('click', handleOverlayClick);
        }

        // Quote form submission
        if (quote_form) {
            quote_form.addEventListener('submit', handleFormSubmit);
        }
    }

    /**
     * Handle product button click
     */
    // function handleProductButtonClick() {
    //     const product_id = this.getAttribute('data-product-id');

    //     if (!product_id) {
    //         console.log('Product ID not found!');
    //         return;
    //     }

    //     openPopup(product_id);
    // }

    /**
     * Handle cart button click
     */
    function handleCartButtonClick() {
        const cart_count = this.getAttribute('data-cart-count');
        const parsedCount = parseInt(cart_count, 10);

        if (isNaN(parsedCount) || parsedCount <= 0) {
            console.log('No products found in cart!');
            return;
        }

        openPopup(cart_count, true);
    }

    function handleProductButtonClick() {

        const product_id = this.getAttribute('data-product-id');

        if (!product_id) {
            console.log('no product id found!');
            return;
        }

        openPopup(product_id, false);

    }
    function openPopup(data, is_cart) {
        if (!popup_overlay || !quote_form || (is_cart && !cart_count_input)) {
            console.error('Popup elements not found!');


            return;
        }

        console.log('cart count : ' + data);
        // Reset form and response
        quote_form.reset();
        form_response.innerHTML = '';

        // Set the data value
        if (is_cart) {
            console.log('cart page is: ' + is_cart);

            cart_count_input.value = data;
        }

        else if (!is_cart) {
            console.log('product page is: ' + is_cart);
            product_id_input.value = data;
        }



        // Show popup
        popup_overlay.style.display = 'flex';
    }

    /**
     * Close popup
     */
    function closePopup() {
        if (popup_overlay) {
            popup_overlay.style.display = 'none';
        }
    }

    function handleOverlayClick(e) {
        if (e.target === popup_overlay) {
            closePopup();
        }
    }

    function handleFormSubmit(e) {
        e.preventDefault();

        const submit_button = document.getElementById('wcgpq-submit-btn');

        if (!submit_button) {
            console.error('Submit button not found!');
            return;
        }

        const original_text = submit_button.textContent;

        // Disable button and show loading state
        submit_button.textContent = "Sending...";
        submit_button.disabled = true;

        const form_data = new FormData(quote_form);
        form_data.append('action', 'wcgpq_send_quote');
        form_data.append('nonce', wcgpq_product_quote_data.nonce);

        // Send AJAX request
        sendQuoteRequest(form_data, submit_button, original_text);
    }

    function sendQuoteRequest(form_data, submit_button, original_text) {
        fetch(wcgpq_product_quote_data.ajaxurl, {
            method: 'POST',
            body: form_data,
            credentials: 'same-origin'
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error("Network response was not OK");
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    showMessage('Email sent successfully!', 'success');

                    setTimeout(() => {
                        quote_form.reset();
                        closePopup();
                        form_response.innerHTML = '';
                    }, 2000);
                } else {
                    showMessage('Error: ' + data.data, 'error');
                }

                resetButton(submit_button, original_text);
            })
            .catch(error => {
                console.error('Error: ' + error);
                showMessage('Network error occurred!', 'error');
                resetButton(submit_button, original_text);
            });
    }

    /**
     * Show message to user
     * @param {string} message - Message to display
     * @param {string} type - Message type ('success' or 'error')
     */
    function showMessage(message, type) {
        if (!form_response) return;

        const color = type === 'success' ? 'green' : 'red';
        form_response.innerHTML = '<p style="color:' + color + '; font-weight:bold;">' + message + '</p>';
        form_response.style.display = 'block';

        setTimeout(() => {
            form_response.style.display = 'none';
        }, 5000);
    }

    /**
     * Reset button to original state
     * @param {HTMLElement} button - Button element to reset
     * @param {string} text - Original button text
     */
    function resetButton(button, text) {
        if (!button) return;

        button.textContent = text;
        button.disabled = false;
    }
});