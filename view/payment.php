<?php
    session_start();
    if(!isset($_COOKIE['status'])){
        header('location: ../view/login.php?error=badrequest');
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

<body>
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

    <div class="main">
        <div class="container">
            <h2>Checkout</h2>
            <p>Course: <b>Advanced JavaScript</b> | Price: <b>$49</b></p>

            
            <form method="post" action="../controllers/pay.php" id="paymentForm" onsubmit="processPayment(event)">
                <label for="coupon">Enter Coupon Code:</label><br>
                <input type="text" id="coupon" name="coupon" placeholder="COUPON123">
                <button type="button" onclick="applyCoupon()">Apply Coupon</button>
                <p id="discount-msg"></p>

                <h3>Select Payment Method:</h3>
                <select id="paymentMethod" name="paymentMethod" onchange="togglePaymentDetails()">
                    <option value="card">Credit/Debit Card</option>
                    <option value="paypal">PayPal</option>
                </select>

                <div id="cardDetails" style="display:block;">
                    <input type="text" id="cardNumber" name="cardNumber" placeholder="Card Number" required>
                    <input type="text" id="expiry" name="expiry" placeholder="MM/YY" required>
                    <input type="text" id="cvv" name="cvv" placeholder="CVV" required>
                </div>

                <div id="paypalDetails" style="display:none;">
                    <p>You'll be redirected to PayPal to complete your payment.</p>
                </div>

                <button type="submit">Pay Now</button>
            </form>

            <!-- ✅ Invoice Section -->
            <div id="invoice" style="display:none;">
                <h3>Invoice</h3>
                <p>Course: Advanced JavaScript</p>
                <p>Paid By: <span id="payerName"></span></p>
                <p>Amount: $<span id="paidAmount">49</span></p>
                <p>Date: <span id="paidDate"></span></p>
                <p>Payment Method: <span id="paidMethod"></span></p>
            </div>
        </div>
    </div>

    <footer>
        <p>&copy; 2025 CodeCraft. All Rights Reserved.</p>
    </footer>

    <script src="../assets/js/payment.js"></script>
</body>
</html>
