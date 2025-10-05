  let finalPrice = 49;

  function togglePaymentDetails() {
    const method = document.getElementById('paymentMethod').value;
    const cardDetails = document.getElementById('cardDetails');
    const paypalDetails = document.getElementById('paypalDetails');

  if (method === 'card') {
        cardDetails.style.display = 'block';
        paypalDetails.style.display = 'none';
        cardDetails.querySelectorAll('input').forEach(i => i.required = true);
    } else {
        cardDetails.style.display = 'none';
        paypalDetails.style.display = 'block';
        cardDetails.querySelectorAll('input').forEach(i => i.required = false);
    }
}

  function applyCoupon() {
    const coupon = document.getElementById('coupon').value.trim();
    const msg = document.getElementById('discount-msg');
    if(coupon === "DISCOUNT10") {                                            //DISCOUNT10
      finalPrice = 49 - 10;
      msg.innerText = "Coupon applied! New Price: $" + finalPrice;}
    else if(coupon === ""){
      msg.innerText = "";
      finalPrice = 49;
    }
    else {
      msg.innerText = "Invalid coupon code!";
    }
  }

  function validatePaymentForm() {
    const method = document.getElementById('paymentMethod').value;
 
    if(method === 'card') {
      const cardNumber = document.getElementById('cardNumber').value;
      const expiry = document.getElementById('expiry').value;
      const cvv = document.getElementById('cvv').value;
      if(!cardNumber || !expiry || !cvv) {
        alert("Please fill card details!");
        return;
      }
      alert("Processing Card Payment...");
    } 
    if (!/^\d{16}$/.test(cardNumber)) {
            alert("Card number must be 16 digits!");
            return false;
        }
        if (!/^\d{2}\/\d{2}$/.test(expiry)) {
            alert("Expiry must be in MM/YY format!");
            return false;
        }
        if (!/^\d{3,4}$/.test(cvv)) {
            alert("CVV must be 3 or 4 digits!");
            return false;
        }
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
  