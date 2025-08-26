document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('enrollmentForm');
    const paymentMethodSelect = document.getElementById('paymentMethod');
    const creditCardInfo = document.getElementById('creditCardInfo');

    paymentMethodSelect.addEventListener('change', function () {
        if (this.value === 'creditCard') {
            creditCardInfo.classList.remove('hidden');
        } else {
            creditCardInfo.classList.add('hidden');
        }
    });

    form.addEventListener('submit', function (event) {
        event.preventDefault();
        if (validateForm()) {
            alert('Enrollment successful!');
            form.reset();
            creditCardInfo.classList.add('hidden');
        }
    });

    function validateForm() {
        let isValid = true;
        
        // Clear previous errors
        document.querySelectorAll('.error-message').forEach(el => el.textContent = '');
        document.querySelectorAll('input, select').forEach(el => el.style.borderColor = '#ccc');

        // Full Name Validation
        const fullName = document.getElementById('fullName');
        if (fullName.value.trim() === '') {
            document.getElementById('fullNameError').textContent = 'Full Name is required.';
            fullName.style.borderColor = '#e74c3c';
            isValid = false;
        }

        // Email Validation
        const email = document.getElementById('email');
        const emailPattern = /^[^\\s@]+@[^\\s@]+\\.[^\\s@]+$/;
        if (!emailPattern.test(email.value)) {
            document.getElementById('emailError').textContent = 'Please enter a valid email address.';
            email.style.borderColor = '#e74c3c';
            isValid = false;
        }

        // Course Selection Validation
        const courseSelect = document.getElementById('courseSelect');
        if (courseSelect.value === '') {
            document.getElementById('courseSelectError').textContent = 'Please select a course.';
            courseSelect.style.borderColor = '#e74c3c';
            isValid = false;
        }

        // Payment Method Validation
        const paymentMethod = document.getElementById('paymentMethod');
        if (paymentMethod.value === '') {
            document.getElementById('paymentMethodError').textContent = 'Please select a payment method.';
            paymentMethod.style.borderColor = '#e74c3c';
            isValid = false;
        } else if (paymentMethod.value === 'creditCard') {
            // Card Number Validation
            const cardNumber = document.getElementById('cardNumber');
            const cardNumberPattern = /^[0-9]{13,16}$/;
            if (!cardNumberPattern.test(cardNumber.value.replace(/-/g, ''))) {
                document.getElementById('cardNumberError').textContent = 'Please enter a valid card number.';
                cardNumber.style.borderColor = '#e74c3c';
                isValid = false;
            }

            // Expiry Date Validation
            const expiryDate = document.getElementById('expiryDate');
            const expiryDatePattern = /^(0[1-9]|1[0-2])\\/([0-9]{2})$/;
            if (!expiryDatePattern.test(expiryDate.value)) {
                document.getElementById('expiryDateError').textContent = 'Please enter a valid expiry date (MM/YY).';
                expiryDate.style.borderColor = '#e74c3c';
                isValid = false;
            }

            // CVV Validation
            const cvv = document.getElementById('cvv');
            const cvvPattern = /^[0-9]{3,4}$/;
            if (!cvvPattern.test(cvv.value)) {
                document.getElementById('cvvError').textContent = 'Please enter a valid CVV.';
                cvv.style.borderColor = '#e74c3c';
                isValid = false;
            }
        }

        return isValid;
    }
});