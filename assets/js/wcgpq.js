// Product Quote Button js

document.addEventListener('DOMContentLoaded', function () {

    const product_quote_button = document.querySelector('.wcgpq-button');
    const product_quote_message = document.querySelector('.wcgpq-message');

    if (!product_quote_button) {
        console.log('Product-quote-button not found !');
        return;
    }

    product_quote_button.addEventListener('click', handleButtonClick);


    function handleButtonClick() {

        const product_id = this.getAttribute('data-product-id');

        if (!product_id) {
            showMessage('Product ID not found', 'error')
            console.log('Product id not founnd !');
            return;
        }

        const original_text = this.textContent;
        this.textContent = 'sending ...';
        this.disabled = true;


        sendEmail(product_id, original_text);

    }


    /**
     * AJAX request 
     * @param {*} product_id 
     * @param {*} original_text 
     */
    function sendEmail(product_id, original_button_text) {

        const form_data = new FormData();

        form_data.append('action', 'send_product_email');
        form_data.append('product_id', product_id);
        form_data.append('nonce', 'wcgpq-product_quote_data_nonce');

        // request using FETCH API
        fetch(wcgpq_product_quote_data.ajaxUrl, {
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
                }
                else {
                    showMessage('Error: ' + data.data, 'error')
                }

                resetButton(original_button_text);
            }).catch(error => {
                console.error('Error : ' + error);
                showMessage('Network Error occurred ! ', 'error');
                resetButton(original_button_text);

            })
    }

    function showMessage(message, type) {
        const color = type === 'success' ? 'green' : 'red';
        product_quote_message.innerHTML = '<p style="color:' + color + '; font-weight:bold;">' + message + '</p>';
        product_quote_message.style.display = 'block';

        setTimeout(() => {
            product_quote_message.style.display = 'none';
        }, 5000);

    }

    function resetButton(text) {
        product_quote_button.textContent = text;
        product_quote_button.disabled = false;
    }
});
