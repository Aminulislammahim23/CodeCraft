  let finalPrice = 49;

  document.getElementById('paymentMethod').addEventListener('change', function() {
    if(this.value === 'card') {
      document.getElementById('cardDetails').style.display = 'block';
      document.getElementById('paypalDetails').style.display = 'none';
    } else {
      document.getElementById('cardDetails').style.display = 'none';
      document.getElementById('paypalDetails').style.display = 'block';
    }
  });

  function applyCoupon() {
    const coupon = document.getElementById('coupon').value.trim();
    const msg = document.getElementById('discount-msg');
    if(coupon === "DISCOUNT10") {
      finalPrice = 49 - 10;
      msg.innerText = "Coupon applied! New Price: $" + finalPrice;
    } else {
      msg.innerText = "Invalid coupon code!";
    }
  }

  function processPayment() {
    const method = document.getElementById('paymentMethod').value;
    // Simple validation
    if(method === 'card') {
      const cardNumber = document.getElementById('cardNumber').value;
      const expiry = document.getElementById('expiry').value;
      const cvv = document.getElementById('cvv').value;
      if(!cardNumber || !expiry || !cvv) {
        alert("Please fill card details!");
        return;
      }
      alert("Processing Card Payment...");
    } else {
      alert("Redirecting to PayPal...");
    }

    // Simulate successful payment
    setTimeout(() => {
      const date = new Date().toLocaleDateString();
      document.getElementById('payerName').innerText = "John Doe"; // Replace with actual user
      document.getElementById('paidAmount').innerText = finalPrice;
      document.getElementById('paidDate').innerText = date;
      document.getElementById('paidMethod').innerText = method === 'card' ? 'Card' : 'PayPal';
      document.getElementById('invoice').style.display = 'block';
      alert("Payment Successful! Invoice generated.");
    }, 1000);
  }