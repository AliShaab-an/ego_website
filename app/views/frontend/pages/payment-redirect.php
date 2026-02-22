<?php
// Prevent browser caching of payment redirect page
// This ensures users don't accidentally resubmit stale payment requests
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Expires: Thu, 01 Jan 1970 00:00:00 GMT");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redirecting to Secure Payment...</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 20px;
        }
        
        .redirect-container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
            padding: 40px;
            max-width: 500px;
            width: 100%;
            text-align: center;
        }
        
        .logo {
            margin-bottom: 30px;
        }
        
        .logo img {
            max-width: 150px;
            height: auto;
        }
        
        .spinner {
            width: 60px;
            height: 60px;
            border: 4px solid #f3f3f3;
            border-top: 4px solid #667eea;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin: 0 auto 30px;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        h1 {
            color: #333;
            font-size: 24px;
            margin-bottom: 15px;
            font-weight: 600;
        }
        
        p {
            color: #666;
            font-size: 16px;
            line-height: 1.6;
            margin-bottom: 20px;
        }
        
        .order-info {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
            text-align: left;
        }
        
        .order-info-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #e9ecef;
        }
        
        .order-info-row:last-child {
            border-bottom: none;
        }
        
        .order-info-label {
            color: #666;
            font-weight: 500;
        }
        
        .order-info-value {
            color: #333;
            font-weight: 600;
        }
        
        .secure-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #e8f5e9;
            color: #2e7d32;
            padding: 10px 20px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
            margin-top: 20px;
        }
        
        .secure-badge svg {
            width: 20px;
            height: 20px;
        }
        
        .warning-message {
            background: #fff3cd;
            color: #856404;
            padding: 15px;
            border-radius: 8px;
            border: 1px solid #ffeaa7;
            margin-top: 20px;
            font-size: 14px;
        }
        
        noscript {
            color: #dc3545;
            display: block;
            margin-top: 20px;
            padding: 15px;
            background: #f8d7da;
            border-radius: 8px;
            border: 1px solid #f5c6cb;
        }
        
        .manual-submit {
            margin-top: 30px;
            padding: 15px;
            background: #fff3cd;
            border-radius: 8px;
            border: 1px solid #ffeaa7;
        }
        
        .manual-submit button {
            background: #667eea;
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 6px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s ease;
            margin-top: 10px;
        }
        
        .manual-submit button:hover {
            background: #5568d3;
        }
    </style>
</head>
<body>
    <div class="redirect-container">
        <div class="logo">
            <!-- Replace with your logo -->
            <img src="/public/assets/images/logo.png" alt="Logo" onerror="this.style.display='none'">
        </div>
        
        <div class="spinner"></div>
        
        <h1>Redirecting to Secure Payment</h1>
        
        <p>Please wait while we redirect you to our secure payment gateway to complete your transaction.</p>
        
        <?php if (isset($orderInfo)): ?>
        <div class="order-info">
            <div class="order-info-row">
                <span class="order-info-label">Order Number:</span>
                <span class="order-info-value"><?= htmlspecialchars($orderInfo['order_number'] ?? 'N/A') ?></span>
            </div>
            <div class="order-info-row">
                <span class="order-info-label">Amount:</span>
                <span class="order-info-value">$<?= number_format($orderInfo['amount'] ?? 0, 2) ?></span>
            </div>
            <div class="order-info-row">
                <span class="order-info-label">Payment Method:</span>
                <span class="order-info-value">Bank Transfer (eCheck)</span>
            </div>
        </div>
        <?php endif; ?>
        
        <div class="secure-badge">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                <path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm0 10.99h7c-.53 4.12-3.28 7.79-7 8.94V12H5V6.3l7-3.11v8.8z"/>
            </svg>
            Secured by Cybersource
        </div>
        
        <div class="warning-message">
            <strong>Important:</strong> Do not close this window or press the back button during the payment process.
        </div>
        
        <!-- Hidden auto-submit form -->
        <form id="payment_form" method="POST" action="<?= htmlspecialchars($checkoutUrl ?? '') ?>">
            <?php if (isset($checkoutFields) && is_array($checkoutFields)): ?>
                <?php foreach ($checkoutFields as $name => $value): ?>
                    <input type="hidden" name="<?= htmlspecialchars($name) ?>" value="<?= htmlspecialchars($value) ?>">
                <?php endforeach; ?>
            <?php endif; ?>
        </form>
        
        <noscript>
            <div class="manual-submit">
                <p><strong>JavaScript is disabled.</strong></p>
                <p>Please click the button below to proceed to payment:</p>
                <button type="submit" form="payment_form">Continue to Payment</button>
            </div>
        </noscript>
    </div>
    
    <script>
        // Auto-submit form after 2 seconds
        window.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('payment_form');
            
            if (!form) {
                console.error('Payment form not found');
                alert('Payment form not found. Please contact support.');
                return;
            }
            
            // Add loading indicator
            console.log('Redirecting to payment gateway in 2 seconds...');
            
            // Auto-submit after 2 seconds
            setTimeout(function() {
                console.log('Submitting payment form...');
                form.submit();
            }, 2000);
        });
        
        // Prevent form resubmission on page reload
        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }
    </script>
</body>
</html>
