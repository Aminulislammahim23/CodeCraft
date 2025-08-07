const cardDetails = document.getElementById('cardDetails');
    const paypalDetails = document.getElementById('paypalDetails');
    const paymentMethods = document.querySelectorAll('input[name="paymentMethod"]');

    paymentMethods.forEach(method => {
      method.addEventListener('change', () => {
        if (method.value === 'card') {
          cardDetails.style.display = 'block';
          paypalDetails.style.display = 'none';
        } else {
          cardDetails.style.display = 'none';
          paypalDetails.style.display = 'block';
        }
      });
    });

    function applyCoupon() {
      alert('Coupon applied successfully!');
    }

    function processPayment() {
      alert('Redirecting to secure payment gateway...');
    }