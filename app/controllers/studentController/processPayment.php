<?php
session_start();
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../helpers/asset_helper.php';
require_once __DIR__ . '/../../models/courseModel.php';
require_once __DIR__ . '/../../models/paymentModel.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'student') {
    header("Location: " . controller_url('auth/login.php'));
    exit();
}
$courseId = $_POST['course_id'] ?? $_GET['course_id'] ?? 0;
$paymentType = $_POST['payment_type'] ?? 'full';
if ($courseId <= 0) {
    header("Location: " . controller_url('studentController/dashboard.php?error=invalid_course'));
    exit();
}
$course = getCourseById($courseId);
if (!$course) {
    header("Location: " . controller_url('studentController/dashboard.php?error=course_not_found'));
    exit();
}
$paymentAmount = $course['price'];
$csrf_token = bin2hex(random_bytes(32));
$_SESSION['csrf_token'] = $csrf_token;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secure Payment | Student Dashboard</title>
    <link rel="stylesheet" href="<?php echo css_url('student.css'); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .payment-container {
            max-width: 800px;
            margin: 40px auto;
            padding: 30px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        .payment-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .payment-header h1 {
            color: #4f46e5;
            margin-bottom: 10px;
        }
        .payment-summary {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
        }
        .payment-methods {
            margin-bottom: 25px;
        }
        .payment-options {
            display: flex;
            gap: 15px;
            margin-top: 10px;
        }
        .payment-option {
            flex: 1;
            padding: 15px;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-align: center;
        }
        .payment-option:hover {
            border-color: #4f46e5;
        }
        .payment-option input[type="radio"] {
            display: none;
        }
        .payment-option.active {
            border-color: #4f46e5;
            background-color: #eff6ff;
        }
        .payment-option.active .payment-label {
            color: #4f46e5;
            font-weight: 600;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #334155;
        }
        .form-group input {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            font-size: 16px;
            transition: border-color 0.3s ease;
        }
        .form-group input:focus {
            outline: none;
            border-color: #4f46e5;
        }
        .security-note {
            background: #dbeafe;
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
            font-size: 14px;
            color: #1e40af;
        }
        .coupon-section {
            display: flex;
            gap: 10px;
            margin-top: 10px;
        }
        .coupon-section input {
            flex: 1;
        }
        .coupon-section button {
            padding: 12px 20px;
            background: #4f46e5;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
        }
        .final-amount {
            background: #dcfce7;
            padding: 15px;
            border-radius: 8px;
            text-align: center;
            margin: 20px 0;
            font-size: 18px;
            font-weight: bold;
            color: #166534;
        }
        .back-to-dashboard {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background: #6c757d;
            color: white;
            text-decoration: none;
            border-radius: 8px;
        }
        .back-to-dashboard:hover {
            background: #5a6268;
        }
        @media (max-width: 768px) {
            .payment-container {
                margin: 20px;
                padding: 20px;
            }
            .payment-options {
                flex-direction: column;
            }
            .coupon-section {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="admin-container">
        
        <aside class="sidebar">
            <img src="<?php echo img_url('logo.png'); ?>" class="brand-logo">
            <h2 class="logo">Welcome to CodeCraft</h2>
            <ul class="menu">
                <li>
                    <a href="<?php echo controller_url('studentController/dashboard.php'); ?>">📊 Dashboard</a>
                </li>
                <li class="active">
                    <a href="<?php echo controller_url('studentController/dashboard.php#courses'); ?>">📚 Courses</a>
                </li>
                <li>
                    <a href="<?php echo controller_url('studentController/dashboard.php#enrollments'); ?>">📦 Enrollments</a>
                </li>
                <li>
                    <a href="profile.php">👤 Profile</a>
                </li>
                <li>
                    <a href="<?php echo controller_url('logout.php'); ?>">🚪 Logout</a>
                </li>
            </ul>
        </aside>
        <main class="main">
            
            <header class="topbar">
                <h1>Secure Payment</h1>
                <div class="student-info">
                    <span><?php echo htmlspecialchars($_SESSION['full_name'] ?? 'Student'); ?></span>
                </div>
            </header>
            <div class="payment-container">
                <div class="payment-header">
                    <h1><i class="fas fa-lock"></i> Secure Payment</h1>
                    <p>Complete your enrollment in <strong><?php echo htmlspecialchars($course['title']); ?></strong></p>
                </div>
                <div class="payment-summary">
                    <h3><i class="fas fa-receipt"></i> Payment Summary</h3>
                    <p><strong>Course:</strong> <?php echo htmlspecialchars($course['title']); ?></p>
                    <p><strong>Amount:</strong> $<?php echo number_format($paymentAmount, 2); ?></p>
                    <p><strong>Difficulty:</strong> <?php echo htmlspecialchars($course['difficulty']); ?></p>
                    <p><strong>Duration:</strong> <?php echo htmlspecialchars($course['duration']); ?></p>
                </div>
                <div class="security-note">
                    <i class="fas fa-shield-alt"></i> Your payment information is secured with 256-bit SSL encryption
                </div>
                <form id="payment-form" method="POST" action="../processPayment.php">
                    <input type="hidden" name="course_id" value="<?php echo $course['id']; ?>">
                    <input type="hidden" name="amount" value="<?php echo $paymentAmount; ?>">
                    <input type="hidden" name="payment_type" value="<?php echo $paymentType; ?>">
                    <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                    <div class="payment-methods">
                        <h3><i class="fas fa-credit-card"></i> Select Payment Method</h3>
                        <div class="payment-options">
                            <label class="payment-option active">
                                <input type="radio" name="payment_method" value="card" checked>
                                <i class="fas fa-credit-card fa-2x"></i>
                                <span class="payment-label">Credit/Debit Card</span>
                            </label>
                            <label class="payment-option">
                                <input type="radio" name="payment_method" value="paypal">
                                <i class="fab fa-paypal fa-2x"></i>
                                <span class="payment-label">PayPal</span>
                            </label>
                        </div>
                    </div>
                    <div id="card-details">
                        <div class="form-group">
                            <label for="card-number"><i class="fas fa-hashtag"></i> Card Number</label>
                            <input type="text" id="card-number" name="card_number" placeholder="1234 5678 9012 3456" maxlength="19" required>
                        </div>
                        <div class="form-group">
                            <label for="card-holder"><i class="fas fa-user"></i> Cardholder Name</label>
                            <input type="text" id="card-holder" name="card_holder" placeholder="John Doe" required>
                        </div>
                        <div class="form-group">
                            <label for="expiry"><i class="fas fa-calendar"></i> Expiry Date (MM/YY)</label>
                            <input type="text" id="expiry" name="expiry" placeholder="MM/YY" maxlength="5" required>
                        </div>
                        <div class="form-group">
                            <label for="cvv"><i class="fas fa-lock"></i> CVV</label>
                            <input type="text" id="cvv" name="cvv" placeholder="123" maxlength="4" required>
                        </div>
                    </div>
                    <div id="paypal-details" style="display:none;">
                        <div class="paypal-info">
                            <p><i class="fab fa-paypal"></i> You will be redirected to PayPal to complete your payment securely.</p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="coupon"><i class="fas fa-tag"></i> Coupon Code (Optional)</label>
                        <div class="coupon-section">
                            <input type="text" id="coupon" name="coupon_code" placeholder="Enter coupon code">
                            <button type="button" id="apply-coupon"><i class="fas fa-check"></i> Apply</button>
                        </div>
                    </div>
                    <div class="final-amount">
                        <i class="fas fa-dollar-sign"></i> Final Amount: $<span id="final-amount"><?php echo number_format($paymentAmount, 2); ?></span>
                    </div>
                    <button type="submit" class="enroll-btn" style="font-size: 18px; padding: 15px; width: 100%;">
                        <i class="fas fa-check-circle"></i> Complete Payment - $<?php echo number_format($paymentAmount, 2); ?>
                    </button>
                </form>
            </div>
        </main>
    </div>
    <script>
        document.querySelectorAll('input[name="payment_method"]').forEach(radio => {
            radio.addEventListener('change', function() {
                document.querySelectorAll('.payment-option').forEach(option => {
                    option.classList.remove('active');
                });
                if (this.checked) {
                    this.closest('.payment-option').classList.add('active');
                }
                if (this.value === 'card') {
                    document.getElementById('card-details').style.display = 'block';
                    document.getElementById('paypal-details').style.display = 'none';
                } else if (this.value === 'paypal') {
                    document.getElementById('card-details').style.display = 'none';
                    document.getElementById('paypal-details').style.display = 'block';
                }
            });
        });
        document.getElementById('card-number').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 16) value = value.substring(0, 16);
            let formattedValue = '';
            for (let i = 0; i < value.length; i++) {
                if (i > 0 && i % 4 === 0) formattedValue += ' ';
                formattedValue += value[i];
            }
            e.target.value = formattedValue;
        });
        document.getElementById('expiry').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 4) value = value.substring(0, 4);
            if (value.length >= 2) {
                value = value.substring(0, 2) + '/' + value.substring(2);
            }
            e.target.value = value;
        });
        document.getElementById('apply-coupon').addEventListener('click', function() {
            const couponCode = document.getElementById('coupon').value.trim();
            if (couponCode !== '') {
                alert('Validating coupon: ' + couponCode);
                alert('Coupon applied successfully!');
            }
        });
        document.getElementById('payment-form').addEventListener('submit', function(e) {
            e.preventDefault();
            const cardNumber = document.getElementById('card-number').value.replace(/\s/g, '');
            const cardHolder = document.getElementById('card-holder').value;
            const expiry = document.getElementById('expiry').value;
            const cvv = document.getElementById('cvv').value;
            if (document.querySelector('input[name="payment_method"]:checked').value === 'card') {
                if (cardNumber.length !== 16) {
                    alert('Please enter a valid 16-digit card number');
                    return;
                }
                if (cardHolder.length < 3) {
                    alert('Please enter a valid cardholder name');
                    return;
                }
                if (!expiry.match(/^\d{2}\/\d{2}$/)) {
                    alert('Please enter a valid expiry date (MM/YY)');
                    return;
                }
                if (cvv.length < 3 || cvv.length > 4) {
                    alert('Please enter a valid CVV (3-4 digits)');
                    return;
                }
            }
            const submitButton = this.querySelector('button[type="submit"]');
            const originalText = submitButton.innerHTML;
            submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing Payment...';
            submitButton.disabled = true;
            this.submit();
        });
    </script>
</body>
</html>