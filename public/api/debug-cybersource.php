<?php
/**
 * Cybersource Debug/Self-Test Endpoint
 * 
 * PURPOSE: Verify Cybersource configuration and test signature generation
 * ACCESS: Admin only (requires Authorization header with valid admin token)
 * 
 * USAGE:
 * GET /api/debug-cybersource.php
 * Authorization: Bearer <admin_jwt_token>
 * 
 * RETURNS:
 * - Current server time vs generated signed_date_time
 * - Test transaction UUID
 * - Computed data_to_sign string
 * - Computed signature for verification
 * - Environment configuration details (masked)
 */

require_once __DIR__ . '/../../app/bootstrap.php';

// Set JSON response headers
header('Content-Type: application/json');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header("Pragma: no-cache");

try {
    // STEP 1: Verify admin authorization (session-based)
    // Start session if not already started
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    if (!Auth::isAdmin()) {
        http_response_code(403);
        echo json_encode([
            'success' => false,
            'error' => 'Forbidden: Admin access required. Please login at /admin/login.php'
        ]);
        exit;
    }
    
    $currentAdmin = Auth::user();
    Logger::info('Debug Cybersource Endpoint Accessed', [
        'admin_id' => $currentAdmin['id'] ?? 'unknown',
        'admin_email' => $currentAdmin['email'] ?? 'unknown'
    ]);
    
    // STEP 2: Load Cybersource configuration
    $profileId = $_ENV['CYBS_PROFILE_ID'] ?? '';
    $accessKey = $_ENV['CYBS_ACCESS_KEY'] ?? '';
    $secretKey = $_ENV['CYBS_SECRET_KEY'] ?? '';
    $endpoint = $_ENV['CYBS_ENDPOINT'] ?? '';
    
    if (empty($profileId) || empty($accessKey) || empty($secretKey) || empty($endpoint)) {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'error' => 'Configuration Error: Missing Cybersource credentials in .env',
            'missing' => [
                'CYBS_PROFILE_ID' => empty($profileId),
                'CYBS_ACCESS_KEY' => empty($accessKey),
                'CYBS_SECRET_KEY' => empty($secretKey),
                'CYBS_ENDPOINT' => empty($endpoint)
            ]
        ]);
        exit;
    }
    
    // STEP 3: Generate test parameters (same as real request)
    $testUuid = sprintf(
        '%s%s-%s-%s-%s-%s%s%s',
        ...str_split(bin2hex(random_bytes(16)), 4)
    );
    
    $signedDateTime = gmdate("Y-m-d\TH:i:s\Z");
    
    // Create test payment parameters (minimal set)
    $testParams = [
        'access_key' => $accessKey,
        'profile_id' => $profileId,
        'transaction_uuid' => $testUuid,
        'signed_field_names' => 'access_key,profile_id,transaction_uuid,signed_field_names,unsigned_field_names,signed_date_time,locale,transaction_type,reference_number,amount,currency,payment_method,bill_to_forename,bill_to_surname,bill_to_email,bill_to_address_line1,bill_to_address_city,bill_to_address_state,bill_to_address_country,bill_to_address_postal_code,override_custom_receipt_page,merchant_defined_data1',
        'unsigned_field_names' => 'customer_ip_address',
        'signed_date_time' => $signedDateTime,
        'locale' => 'en',
        'transaction_type' => 'sale,create_payment_token',
        'reference_number' => 'TEST-' . time(),
        'amount' => '100.00',
        'currency' => 'USD',
        'payment_method' => 'echeck',
        'bill_to_forename' => 'Test',
        'bill_to_surname' => 'User',
        'bill_to_email' => 'test@example.com',
        'bill_to_address_line1' => '123 Test St',
        'bill_to_address_city' => 'Test City',
        'bill_to_address_state' => 'CA',
        'bill_to_address_country' => 'US',
        'bill_to_address_postal_code' => '90210',
        'override_custom_receipt_page' => 'https://example.com/payment-response',
        'merchant_defined_data1' => 'test-order-123',
        'customer_ip_address' => '127.0.0.1'
    ];
    
    // STEP 4: Build data_to_sign string
    $signedFieldNames = $testParams['signed_field_names'];
    $signedFieldNamesArray = explode(',', $signedFieldNames);
    
    $dataToSignArray = [];
    foreach ($signedFieldNamesArray as $field) {
        $field = trim($field);
        $value = $testParams[$field] ?? '';
        $dataToSignArray[] = $field . '=' . $value;
    }
    
    $dataToSign = implode(',', $dataToSignArray);
    
    // STEP 5: Generate signature
    $hash = hash_hmac('sha256', $dataToSign, $secretKey, true);
    $signature = base64_encode($hash);
    
    // STEP 6: Mask sensitive data for response
    $maskKey = function($key) {
        if (strlen($key) <= 8) {
            return str_repeat('*', strlen($key));
        }
        return substr($key, 0, 4) . str_repeat('*', strlen($key) - 8) . substr($key, -4);
    };
    
    // STEP 7: Return debug info
    http_response_code(200);
    echo json_encode([
        'success' => true,
        'message' => 'Cybersource Debug Information',
        'timestamp' => date('Y-m-d H:i:s T'),
        'server_time' => [
            'php_time' => date('Y-m-d H:i:s T'),
            'php_timezone' => date_default_timezone_get(),
            'server_timestamp' => time(),
            'utc_time' => gmdate('Y-m-d H:i:s T'),
            'generated_signed_date_time' => $signedDateTime
        ],
        'configuration' => [
            'endpoint' => $endpoint,
            'environment' => (strpos($endpoint, 'test') !== false) ? 'TEST' : 'PRODUCTION',
            'profile_id' => $profileId,
            'access_key' => $maskKey($accessKey),
            'secret_key' => $maskKey($secretKey)
        ],
        'test_transaction' => [
            'transaction_uuid' => $testUuid,
            'reference_number' => $testParams['reference_number'],
            'amount' => $testParams['amount'],
            'currency' => $testParams['currency']
        ],
        'signature_details' => [
            'signed_field_names' => $signedFieldNames,
            'signed_field_count' => count($signedFieldNamesArray),
            'data_to_sign_length' => strlen($dataToSign),
            'data_to_sign' => $dataToSign,
            'signature' => $signature,
            'signature_algorithm' => 'HMAC-SHA256 + Base64'
        ],
        'validation_checklist' => [
            'credentials_present' => true,
            'endpoint_configured' => !empty($endpoint),
            'signed_date_time_format' => 'ISO 8601 UTC (Y-m-d\TH:i:s\Z)',
            'transaction_uuid_format' => 'UUID v4',
            'signature_algorithm' => 'HMAC SHA256 with base64 encoding'
        ],
        'next_steps' => [
            '1' => 'Copy the signature and data_to_sign values',
            '2' => 'Compare with actual request logs in app/logs/app.log',
            '3' => 'Verify signed_field_names matches between debug and actual requests',
            '4' => 'Verify signed_date_time is not stale (must be within 15 minutes)',
            '5' => 'Check Cybersource Business Center for profile settings',
            '6' => 'Ensure profile_id and access_key match your Secure Acceptance profile'
        ]
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    
} catch (\Exception $e) {
    Logger::error('Debug Cybersource Endpoint Error', [
        'error' => $e->getMessage(),
        'trace' => $e->getTraceAsString()
    ]);
    
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Internal Server Error: ' . $e->getMessage()
    ]);
}
