document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('enrollmentForm');
    const paymentMethodSelect = document.getElementById('paymentMethod');
    const creditCardInfo = document.getElementById('creditCardInfo');
    const inputs = form.querySelectorAll('input, select');

  
    paymentMethodSelect.addEventListener('change', function () {
        creditCardInfo.classList.toggle('hidden', this.value !== 'creditCard');
        if (this.value !== 'creditCard') {
            clearCreditCardErrors();
        }
    });


    inputs.forEach(input => {
        input.addEventListener('input', function () {
            validateField(this);
        });
    });

  
    form.addEventListener('submit', function (event) {
        event.preventDefault();
        if (validateForm()) {
         
            fetch(form.action, {
                method: 'POST',
                body: new FormData(form)
            })
            .then(response => response.text())
            .then(() => {
                window.location.href = '../view/progress.php?success=enrolled';
                form.reset();
                creditCardInfo.classList.add('hidden');
                clearErrors();
            })
            .catch(error => console.error('Error:', error));
        }
    });

    function validateField(field) {
        const errorElement = document.getElementById(field.id + 'Error');
        let isValid = true;

        switch (field.id) {
            case 'fullName':
                isValid = field.value.trim() !== '';
                errorElement.textContent = isValid ? '' : 'Full Name is required.';
                break;
            case 'email':
                const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                isValid = emailPattern.test(field.value);
                errorElement.textContent = isValid ? '' : 'Please enter a valid email address.';
                break;
            case 'courseSelect':
                isValid = field.value !== '';
                errorElement.textContent = isValid ? '' : 'Please select a course.';
                break;
            case 'paymentMethod':
                isValid = field.value !== '';
                errorElement.textContent = isValid ? '' : 'Please select a payment method.';
                if (!isValid) break;
                creditCardInfo.classList.toggle('hidden', field.value !== 'creditCard');
                if (field.value !== 'creditCard') clearCreditCardErrors();
                break;
            case 'cardNumber':
                const cardNumberPattern = /^[0-9]{13,16}$/;
                isValid = cardNumberPattern.test(field.value.replace(/-/g, ''));
                errorElement.textContent = isValid ? '' : 'Please enter a valid card number.';
                break;
            case 'expiryDate':
                const expiryDatePattern = /^(0[1-9]|1[0-2])\/([0-9]{2})$/;
                isValid = expiryDatePattern.test(field.value);
                errorElement.textContent = isValid ? '' : 'Please enter a valid expiry date (MM/YY).';
                break;
            case 'cvv':
                const cvvPattern = /^[0-9]{3,4}$/;
                isValid = cvvPattern.test(field.value);
                errorElement.textContent = isValid ? '' : 'Please enter a valid CVV.';
                break;
        }

        field.style.borderColor = isValid ? '#ccc' : '#e74c3c';
        return isValid;
    }

    function validateForm() {
        let isValid = true;
        clearErrors();

        inputs.forEach(input => {
            if (!validateField(input)) {
                isValid = false;
            }
        });

        if (paymentMethodSelect.value === 'creditCard') {
            const cardFields = ['cardNumber', 'expiryDate', 'cvv'];
            cardFields.forEach(fieldId => {
                const field = document.getElementById(fieldId);
                if (!validateField(field)) {
                    isValid = false;
                }
            });
        }

        return isValid;
    }

    function clearErrors() {
        document.querySelectorAll('.error-message').forEach(el => el.textContent = '');
        inputs.forEach(input => input.style.borderColor = '#ccc');
    }

    function clearCreditCardErrors() {
        ['cardNumber', 'expiryDate', 'cvv'].forEach(id => {
            document.getElementById(id + 'Error').textContent = '';
            document.getElementById(id).style.borderColor = '#ccc';
        });
    }
});
