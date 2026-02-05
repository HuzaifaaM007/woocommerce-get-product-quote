// Product Quote Button js

document.addEventListener('DOMContentLoaded', function () {

    const product_quote_buttons = document.querySelectorAll('.wcgpq-button');

    const popup_overlay = document.getElementById('wcgpq-popup-overlay');
    const popup_close = document.getElementById('wcgpq-close-popup');
    const quote_form = document.getElementById('wcgpq-form');
    const form_response = document.getElementById('wcgpq-form-response');
    const product_id_input = document.getElementById('wcgpq-product-id');

    // const product_quote_message = document.querySelector('.wcgpq-message');

    if (!product_quote_buttons.length) {
        console.log('Product-quote-button not found !');
        return;
    }

    product_quote_buttons.forEach(button => {
        button.addEventListener('click', handleButtonClick);
    });



    function handleButtonClick() {
        const product_id = this.getAttribute('data-product-id');


        if (!product_id) {
            // showMessage('Product ID not found', 'error')
            console.log('Product id not founnd !');
            return;
        }

        product_id_input.value = product_id;

        form_response.innerHTML = '';


        quote_form.reset();
        product_id_input.value = product_id;

        popup_overlay.style.display = 'flex';


        // const original_text = this.textContent;
        // this.textContent = 'sending ...';
        // this.disabled = true;


        // sendEmail(product_id, original_text);

    }

    if (popup_close) {
        popup_close.addEventListener('click', function () {
            popup_overlay.style.display = 'none';
        })
    }

    popup_overlay.addEventListener('click', function (e) {
        if (e.target === popup_overlay) {
            popup_overlay.style.display = 'none';
        }
    });


    // /**
    //  * AJAX request 
    //  * @param {*} product_id 
    //  * @param {*} original_text 
    //  */
    // function sendEmail(product_id, original_button_text) {

    //     const form_data = new FormData();

    //     form_data.append('action', 'send_product_email');
    //     form_data.append('product_id', product_id);
    //     form_data.append('nonce', 'wcgpq-product_quote_data.nonce');

    //     // request using FETCH API
    //     fetch(wcgpq_product_quote_data.ajaxurl, {
    //         method: 'POST',
    //         body: form_data,
    //         credentials: 'same-origin'
    //     })
    //         .then(response => {
    //             if (!response.ok) {
    //                 throw new Error("Network response was not OK");
    //             }
    //             return response.json();
    //         })
    //         .then(data => {
    //             if (data.success) {
    //                 showMessage('Email sent successfully ', 'success');
    //             }
    //             else {
    //                 showMessage('Error: ' + data.data, 'error')
    //             }

    //             resetButton(original_button_text);
    //         }).catch(error => {
    //             console.error('Error : ' + error);
    //             showMessage('Network Error occurred ! ', 'error');
    //             resetButton(original_button_text);

    //         })
    // }

    if (quote_form) {
        quote_form.addEventListener('submit', function (e) {
            e.preventDefault();

            const submit_button = document.getElementById('wcgpq-submit-btn');
            const original_text = submit_button.textContent;

            submit_button.textContent = "sending ...";
            submit_button.disabled = true;

            const form_data = new FormData(quote_form);
            form_data.append('action', 'wcgpq_send_quote');
            form_data.append('nonce', wcgpq_product_quote_data.nonce);

            // request using FETCH API
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
                        showMessage('Email sent successfully ', 'success');

                        setTimeout(() => {
                            quote_form.reset();
                            popup_overlay.style.display = 'none';
                            form_response.innerHTML = '';


                        }, 2000);
                    }
                    else {
                        showMessage('Error: ' + data.data, 'error')
                    }

                    resetButton(submit_button, original_text);
                }).catch(error => {
                    console.error('Error : ' + error);
                    showMessage('Network Error occurred ! ', 'error');
                    // resetButton(original_button_text);
                    resetButton(submit_button, original_text);
                })

        })
    }
    function showMessage(message, type) {
        const color = type === 'success' ? 'green' : 'red';
        form_response.innerHTML = '<p style="color:' + color + '; font-weight:bold;">' + message + '</p>';
        form_response.style.display = 'block';

        setTimeout(() => {
            form_response.style.display = 'none';
        }, 5000);

    }

    function resetButton(submit_button, text) {
        submit_button.textContent = text;
        submit_button.disabled = false;
    }
});
