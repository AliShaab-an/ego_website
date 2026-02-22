<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation - <?= htmlspecialchars($orderNumber ?? '') ?></title>
</head>
<body>
    <div class="order-confirmation-page">
        <div class="container">
            <?php
            $status = $_GET['status'] ?? 'success';
            $paymentStatus = $order['payment_status'] ?? 'pending';
            ?>
            
            <?php if ($status === 'success' && $paymentStatus === 'paid'): ?>
                <!-- SUCCESS -->
                <div class="confirmation-header success">
                    <div class="icon-circle">
                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                            <polyline points="22 4 12 14.01 9 11.01"></polyline>
                        </svg>
                    </div>
                    <h1>Payment Successful!</h1>
                    <p>Thank you for your order. Your payment has been processed successfully.</p>
                </div>
                
            <?php elseif ($status === 'cancelled'): ?>
                <!-- CANCELLED -->
                <div class="confirmation-header warning">
                    <div class="icon-circle">
                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="15" y1="9" x2="9" y2="15"></line>
                            <line x1="9" y1="9" x2="15" y2="15"></line>
                        </svg>
                    </div>
                    <h1>Payment Cancelled</h1>
                    <p>Your payment was cancelled. You can retry checkout if you wish to complete your order.</p>
                </div>
                
            <?php elseif ($status === 'failed' || $paymentStatus === 'failed'): ?>
                <!-- FAILED -->
                <div class="confirmation-header error">
                    <div class="icon-circle">
                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="12" y1="8" x2="12" y2="12"></line>
                            <line x1="12" y1="16" x2="12.01" y2="16"></line>
                        </svg>
                    </div>
                    <h1>Payment Failed</h1>
                    <p>We were unable to process your payment. Please try again or use a different payment method.</p>
                </div>
                
            <?php else: ?>
                <!-- PENDING -->
                <div class="confirmation-header pending">
                    <div class="icon-circle">
                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"></circle>
                            <polyline points="12 6 12 12 16 14"></polyline>
                        </svg>
                    </div>
                    <h1>Order Placed</h1>
                    <p>Your order has been received and is being processed.</p>
                </div>
            <?php endif; ?>
            
            <!-- Order Details -->
            <div class="order-details-card">
                <h2>Order Details</h2>
                
                <div class="detail-row">
                    <span class="label">Order Number:</span>
                    <span class="value"><?= htmlspecialchars($orderNumber ?? 'N/A') ?></span>
                </div>
                
                <div class="detail-row">
                    <span class="label">Order Date:</span>
                    <span class="value"><?= isset($order['created_at']) ? date('F d, Y h:i A', strtotime($order['created_at'])) : 'N/A' ?></span>
                </div>
                
                <div class="detail-row">
                    <span class="label">Payment Method:</span>
                    <span class="value"><?= htmlspecialchars(ucfirst($order['payment_method'] ?? 'N/A')) ?></span>
                </div>
                
                <div class="detail-row">
                    <span class="label">Payment Status:</span>
                    <span class="value">
                        <span class="badge badge-<?= strtolower($paymentStatus) ?>">
                            <?= htmlspecialchars(ucfirst($paymentStatus)) ?>
                        </span>
                    </span>
                </div>
                
                <div class="detail-row">
                    <span class="label">Order Status:</span>
                    <span class="value">
                        <span class="badge badge-<?= strtolower($order['status'] ?? 'pending') ?>">
                            <?= htmlspecialchars(ucfirst($order['status'] ?? 'Pending')) ?>
                        </span>
                    </span>
                </div>
                
                <div class="detail-row total">
                    <span class="label">Total Amount:</span>
                    <span class="value">$<?= number_format($order['total_amount'] ?? 0, 2) ?></span>
                </div>
            </div>
            
            <!-- Transaction Details (if available) -->
            <?php if (!empty($transactions) && $transactions[0]): ?>
                <?php $transaction = $transactions[0]; ?>
                <div class="transaction-details-card">
                    <h2>Payment Transaction</h2>
                    
                    <?php if (!empty($transaction['gateway_transaction_id'])): ?>
                    <div class="detail-row">
                        <span class="label">Transaction ID:</span>
                        <span class="value"><?= htmlspecialchars($transaction['gateway_transaction_id']) ?></span>
                    </div>
                    <?php endif; ?>
                    
                    <div class="detail-row">
                        <span class="label">Gateway:</span>
                        <span class="value"><?= htmlspecialchars(ucfirst($transaction['gateway'] ?? 'N/A')) ?></span>
                    </div>
                    
                    <?php if (!empty($transaction['message'])): ?>
                    <div class="detail-row">
                        <span class="label">Message:</span>
                        <span class="value"><?= htmlspecialchars($transaction['message']) ?></span>
                    </div>
                    <?php endif; ?>
                    
                    <?php if ($paymentStatus === 'paid' && !empty($order['paid_at'])): ?>
                    <div class="detail-row">
                        <span class="label">Paid At:</span>
                        <span class="value"><?= date('F d, Y h:i A', strtotime($order['paid_at'])) ?></span>
                    </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            
            <!-- Action Buttons -->
            <div class="action-buttons">
                <?php if ($status === 'success' && $paymentStatus === 'paid'): ?>
                    <a href="/index.php?page=shop" class="btn btn-primary">Continue Shopping</a>
                    <?php if (Auth::check()): ?>
                        <a href="/index.php?page=my-orders" class="btn btn-secondary">View My Orders</a>
                    <?php endif; ?>
                <?php elseif (in_array($status, ['failed', 'cancelled'])): ?>
                    <a href="/index.php?page=checkout" class="btn btn-primary">Try Again</a>
                    <a href="/index.php?page=contact" class="btn btn-secondary">Contact Support</a>
                <?php else: ?>
                    <a href="/index.php?page=shop" class="btn btn-primary">Continue Shopping</a>
                <?php endif; ?>
            </div>
            
            <!-- Help Text -->
            <div class="help-text">
                <p>
                    <?php if ($paymentStatus === 'pending'): ?>
                        Your payment is being processed. You will receive an email confirmation once the payment is complete.
                    <?php elseif ($paymentStatus === 'paid'): ?>
                        A confirmation email has been sent to <strong><?= htmlspecialchars($order['guest_email'] ?? $user['email'] ?? 'your email') ?></strong>.
                    <?php else: ?>
                        If you need assistance, please contact our support team with your order number.
                    <?php endif; ?>
                </p>
            </div>
        </div>
    </div>
    
    <style>
        .order-confirmation-page {
            padding: 40px 20px;
            background: #f8f9fa;
            min-height: calc(100vh - 200px);
        }
        
        .container {
            max-width: 800px;
            margin: 0 auto;
        }
        
        .confirmation-header {
            text-align: center;
            padding: 40px;
            background: white;
            border-radius: 12px;
            margin-bottom: 30px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }
        
        .icon-circle {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
        }
        
        .confirmation-header.success .icon-circle {
            background: #d4edda;
            color: #155724;
        }
        
        .confirmation-header.error .icon-circle {
            background: #f8d7da;
            color: #721c24;
        }
        
        .confirmation-header.warning .icon-circle {
            background: #fff3cd;
            color: #856404;
        }
        
        .confirmation-header.pending .icon-circle {
            background: #d1ecf1;
            color: #0c5460;
        }
        
        .confirmation-header h1 {
            font-size: 28px;
            margin-bottom: 10px;
            color: #333;
        }
        
        .confirmation-header p {
            font-size: 16px;
            color: #666;
        }
        
        .order-details-card,
        .transaction-details-card {
            background: white;
            border-radius: 12px;
            padding: 30px;
            margin-bottom: 20px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }
        
        .order-details-card h2,
        .transaction-details-card h2 {
            font-size: 20px;
            margin-bottom: 20px;
            color: #333;
            border-bottom: 2px solid #f0f0f0;
            padding-bottom: 10px;
        }
        
        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid #f0f0f0;
        }
        
        .detail-row:last-child {
            border-bottom: none;
        }
        
        .detail-row.total {
            margin-top: 10px;
            padding-top: 15px;
            border-top: 2px solid #333;
            font-size: 18px;
            font-weight: 600;
        }
        
        .detail-row .label {
            color: #666;
            font-weight: 500;
        }
        
        .detail-row .value {
            color: #333;
            font-weight: 600;
        }
        
        .badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }
        
        .badge-paid {
            background: #d4edda;
            color: #155724;
        }
        
        .badge-pending {
            background: #fff3cd;
            color: #856404;
        }
        
        .badge-failed {
            background: #f8d7da;
            color: #721c24;
        }
        
        .badge-cancelled {
            background: #f8d7da;
            color: #721c24;
        }
        
        .badge-unpaid {
            background: #e2e3e5;
            color: #383d41;
        }
        
        .action-buttons {
            display: flex;
            gap: 15px;
            justify-content: center;
            margin: 30px 0;
        }
        
        .btn {
            padding: 12px 30px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            display: inline-block;
        }
        
        .btn-primary {
            background: #667eea;
            color: white;
        }
        
        .btn-primary:hover {
            background: #5568d3;
        }
        
        .btn-secondary {
            background: #6c757d;
            color: white;
        }
        
        .btn-secondary:hover {
            background: #5a6268;
        }
        
        .help-text {
            background: #e7f3ff;
            border-left: 4px solid #2196F3;
            padding: 20px;
            border-radius: 6px;
            margin-top: 30px;
        }
        
        .help-text p {
            margin: 0;
            color: #333;
            line-height: 1.6;
        }
        
        @media (max-width: 768px) {
            .order-confirmation-page {
                padding: 20px 10px;
            }
            
            .confirmation-header {
                padding: 30px 20px;
            }
            
            .order-details-card,
            .transaction-details-card {
                padding: 20px;
            }
            
            .action-buttons {
                flex-direction: column;
            }
            
            .btn {
                width: 100%;
                text-align: center;
            }
        }
    </style>
</body>
</html>
