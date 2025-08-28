<?php
    session_start();
    if(!isset($_COOKIE['status'])){
        header('location: login.php?error=badrequest');
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Payment Checkout - CodeCraft</title>
<link rel="stylesheet" href="../assets/css/payment.css">
</head>


 <header>
        <div class="logo">CodeCraft</div>
        <nav>
            <ul>
                <li><a href="index.html">Home</a></li>
                <li><a href="../view/enrollment.html">Enroll</a></li>
                <li><a href="../view/progress.html">Progress</a></li>
                <li><a href="../view/forum.html">Forums</a></li>
                <li><a href="../view/faq.html">FAQ</a></li>
            </ul>
        </nav>  
    </header>
  <main>
<div class="container">
  <h2>Checkout</h2>
  <p>Course: <b>Advanced JavaScript</b> | Price: <b>$49</b></p>

  <label for="coupon">Enter Coupon Code:</label><br>
  <input type="text" id="coupon" placeholder="COUPON123">
  <button onclick="applyCoupon()">Apply Coupon</button>
  <p id="discount-msg"></p>

  <h3>Select Payment Method:</h3>
  <select id="paymentMethod">
    <option value="card">Credit/Debit Card</option>
    <option value="paypal">PayPal</option>
  </select>

  <div id="cardDetails" style="display:block;">
    <input type="text" id="cardNumber" placeholder="Card Number">
    <input type="text" id="expiry" placeholder="MM/YY">
    <input type="text" id="cvv" placeholder="CVV">
  </div>

  <div id="paypalDetails" style="display:none;">
    <p>You'll be redirected to PayPal to complete your payment.</p>
  </div>

  <button onclick="processPayment()">Pay Now</button>

  <div id="invoice">
    <h3>Invoice</h3>
    <p>Course: Advanced JavaScript</p>
    <p>Paid By: <span id="payerName"></span></p>
    <p>Amount: $<span id="paidAmount">49</span></p>
    <p>Date: <span id="paidDate"></span></p>
    <p>Payment Method: <span id="paidMethod"></span></p>
  </div>
</div>
  </main>
  <footer>
    <p>&copy; 2025 CodeCraft. All Rights Reserved.</p>
  </footer>
<script src="../assets/js/payment.js"></script>
</html>
