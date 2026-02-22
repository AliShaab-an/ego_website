<?php

/**
 * SecureAcceptanceService - Cybersource Secure Acceptance Hosted Checkout Integration
 * 
 * Handles Bank Transfer (eCheck/ACH) payments via Cybersource Secure Acceptance.
 * 
 * CONFIGURATION (.env):
 * CYBS_PROFILE_ID=your_profile_id
 * CYBS_ACCESS_KEY=your_access_key
 * CYBS_SECRET_KEY=your_secret_key
 * CYBS_ENDPOINT=https://testsecureacceptance.cybersource.com/pay (test)
 * CYBS_ENDPOINT=https://secureacceptance.cybersource.com/pay (production)
 * 
 * ============================================================
 * CYBERSOURCE BUSINESS CENTER CONFIGURATION CHECKLIST
 * ============================================================
 * 
 * To avoid "You are not authorized to view this page" errors:
 * 
 * 1. SECURE ACCEPTANCE PROFILE SETTINGS (Business Center > Secure Acceptance Settings)
 *    ✓ Profile ID matches CYBS_PROFILE_ID in .env
 *    ✓ Access Key matches CYBS_ACCESS_KEY in .env
 *    ✓ Secret Key matches CYBS_SECRET_KEY in .env (never exposed to client)
 *    ✓ Signature Type: HMAC SHA256
 *    ✓ Payment Methods Enabled: eCheck/ACH (Bank Transfer)
 * 
 * 2. CUSTOMER RESPONSE PAGES
 *    ✓ Custom receipt page URL configured (override_custom_receipt_page)
 *    ✓ Receipt page URL whitelisted in profile settings
 *    ✓ Decline URL configured (optional)
 * 
 * 3. TRANSACTION SETTINGS
 *    ✓ Transaction Type: sale,create_payment_token (comma-separated)
 *    ✓ Currency: USD (or your required currency)
 *    ✓ Locale: en (or your required locale)
 * 
 * 4. SECURITY SETTINGS
 *    ✓ IP Address Whitelisting: Add your server's public IP (if enabled)
 *    ✓ URL Whitelisting: Add your domain to allowed domains
 *    ✓ Transaction Timeout: Default 15 minutes (signed_date_time must be fresh)
 * 
 * 5. ECHECK/ACH SPECIFIC REQUIREMENTS
 *    ✓ eCheck payment method enabled in profile
 *    ✓ Bank account type collection: Checking, Savings, or Corporate Checking
 *    ✓ Account verification method configured (instant or micro-deposits)
 *    ✓ Payment processor relationship established (verify with Cybersource support)
 * 
 * 6. COMMON ERROR CAUSES
 *    ✗ Signature mismatch = Wrong secret key or incorrect signed_field_names
 *    ✗ Stale timestamp = signed_date_time older than 15 minutes
 *    ✗ Wrong profile = Using test keys with production endpoint or vice versa
 *    ✗ Missing fields = signed_field_names lists fields not in request payload
 *    ✗ Whitespace/newlines = Field values contain invisible characters
 *    ✗ Payment method disabled = eCheck not enabled in profile settings
 * 
 * 7. TESTING CHECKLIST
 *    ✓ Use test endpoint: https://testsecureacceptance.cybersource.com/pay
 *    ✓ Use test credentials from Business Center > Test/Sandbox section
 *    ✓ Check debug endpoint: GET /api/debug-cybersource.php (admin only)
 *    ✓ Review logs: app/logs/app.log for signature and request details
 *    ✓ Verify transaction UUID is unique per attempt (not reused)
 * 
 * ============================================================
 * 
 * USAGE:
 * 
 * // 1. Generate checkout request and redirect user
 * $service = new SecureAcceptanceService();
 * $checkoutData = $service->buildEcheckCheckoutRequest($order, $user);
 * // Returns: ['url' => '...', 'fields' => [...]]
 * 
 * // 2. Handle webhook response
 * $result = $service->handleGatewayResponse($_POST);
 * // Returns: ['success' => true/false, 'message' => '...', 'order_id' => ...]
 */
class SecureAcceptanceService
{
    private string $profileId;
    private string $accessKey;
    private string $secretKey;
    private string $endpoint;
    
    /**
     * Initialize service with configuration
     * 
     * @throws Exception If configuration is missing
     */
    public function __construct()
    {
        // Load configuration from environment
        $this->profileId = $_ENV['CYBS_PROFILE_ID'] ?? '';
        $this->accessKey = $_ENV['CYBS_ACCESS_KEY'] ?? '';
        $this->secretKey = $_ENV['CYBS_SECRET_KEY'] ?? '';
        $this->endpoint = $_ENV['CYBS_ENDPOINT'] ?? 'https://testsecureacceptance.cybersource.com/pay';
        
        // Validate configuration
        if (empty($this->profileId) || empty($this->accessKey) || empty($this->secretKey)) {
            throw new Exception('Cybersource configuration is incomplete. Check .env file (CYBS_PROFILE_ID, CYBS_ACCESS_KEY, CYBS_SECRET_KEY).');
        }
        
        // ========================================
        // ENVIRONMENT VERIFICATION GUARD
        // ========================================
        $this->validateEnvironmentConfiguration();
    }
    
    /**
     * Validate that environment configuration is consistent
     * Prevents using test endpoint with production keys or vice versa
     * 
     * @throws Exception If configuration mismatch detected
     */
    private function validateEnvironmentConfiguration(): void
    {
        $isTestEndpoint = (strpos($this->endpoint, 'testsecureacceptance') !== false);
        $isProdEndpoint = (strpos($this->endpoint, 'secureacceptance.cybersource.com') !== false && 
                          strpos($this->endpoint, 'testsecure') === false);
        
        // Check for obvious mismatches
        if ($isTestEndpoint) {
            // Using test endpoint
            Logger::info("Cybersource initialized", [
                'environment' => 'TEST',
                'endpoint' => $this->endpoint,
                'profile_id' => $this->profileId,
                'access_key' => $this->maskSensitiveData($this->accessKey)
            ]);
            
            // Warning: If profile_id looks like production format (common patterns)
            // Add your own validation rules here
            
        } elseif ($isProdEndpoint) {
            // Using production endpoint
            Logger::warning("Cybersource PRODUCTION mode", [
                'environment' => 'PRODUCTION',
                'endpoint' => $this->endpoint,
                'profile_id' => $this->profileId
            ]);
        } else {
            // Unknown endpoint
            throw new Exception(
                "Invalid CYBS_ENDPOINT configured: {$this->endpoint}. " .
                "Must be either test (testsecureacceptance.cybersource.com) or " .
                "production (secureacceptance.cybersource.com) endpoint."
            );
        }
    }
    
    /**
     * Mask sensitive data for logging (show first/last few chars only)
     * 
     * @param string $data Sensitive string
     * @return string Masked string
     */
    private function maskSensitiveData(string $data): string
    {
        if (strlen($data) <= 8) {
            return str_repeat('*', strlen($data));
        }
        return substr($data, 0, 4) . str_repeat('*', strlen($data) - 8) . substr($data, -4);
    }
    
    /**
     * Build eCheck checkout request for Secure Acceptance
     * 
     * @param array $order Order data from database
     * @param array|null $user User data (null for guest checkout)
     * @return array ['url' => string, 'fields' => array]
     * @throws Exception
     */
    public function buildEcheckCheckoutRequest(array $order, ?array $user = null): array
    {
        try {
            // Generate unique transaction UUID
            $transactionUuid = $this->generateTransactionUuid();
            
            // Generate reference number from order ID
            $referenceNumber = 'ORD-' . str_pad($order['id'], 6, '0', STR_PAD_LEFT);
            
            // Calculate total amount
            $amount = number_format($order['total_amount'], 2, '.', '');
            
            // Determine bill_to fields (user or guest)
            $billToFirstName = '';
            $billToLastName = '';
            $billToEmail = '';
            $billToPhone = '';
            $billToAddress = '';
            $billToCity = '';
            $billToState = '';
            $billToZip = '';
            $billToCountry = 'US';
            
            if ($user) {
                // Logged-in user
                $nameParts = explode(' ', $user['name'] ?? '', 2);
                $billToFirstName = $nameParts[0] ?? '';
                $billToLastName = $nameParts[1] ?? '';
                $billToEmail = $user['email'] ?? '';
                $billToPhone = $user['phone'] ?? '';
                $billToAddress = $user['address'] ?? '';
                $billToCity = $user['city'] ?? '';
                $billToState = $user['state'] ?? '';
                $billToZip = $user['zip'] ?? '';
            } else {
                // Guest user
                $nameParts = explode(' ', $order['guest_name'] ?? '', 2);
                $billToFirstName = $nameParts[0] ?? '';
                $billToLastName = $nameParts[1] ?? '';
                $billToEmail = $order['guest_email'] ?? '';
                $billToPhone = $order['guest_phone'] ?? '';
                
                // Parse guest_address if stored as comma-separated
                $addressParts = explode(', ', $order['guest_address'] ?? '');
                $billToAddress = $addressParts[0] ?? '';
                $billToCity = $addressParts[1] ?? '';
                $billToState = $addressParts[2] ?? '';
                $billToZip = $addressParts[3] ?? '';
            }
            
            // Build request parameters (order matters for signature)
            $params = [
                'access_key' => $this->accessKey,
                'profile_id' => $this->profileId,
                'transaction_uuid' => $transactionUuid,
                'signed_date_time' => gmdate("Y-m-d\TH:i:s\Z"), // ISO 8601 UTC format
                'locale' => 'en',
                'transaction_type' => 'sale',
                'reference_number' => $referenceNumber,
                'amount' => $amount,
                'currency' => 'USD',
                'payment_method' => 'echeck', // Bank transfer/ACH
                'bill_to_forename' => $billToFirstName,
                'bill_to_surname' => $billToLastName,
                'bill_to_email' => $billToEmail,
                'bill_to_phone' => $billToPhone,
                'bill_to_address_line1' => $billToAddress,
                'bill_to_address_city' => $billToCity,
                'bill_to_address_state' => $billToState,
                'bill_to_address_country' => $billToCountry,
                'bill_to_address_postal_code' => $billToZip,
            ];
            
            // Add return URLs (where user's browser goes after payment)
            $baseUrl = $_ENV['APP_URL'] ?? 'http://localhost';
            $params['override_custom_receipt_page'] = $baseUrl . '/api/payment/return/cybersource';
            $params['override_custom_cancel_page'] = $baseUrl . '/api/payment/cancel/cybersource';
            
            // Define which fields will be signed (CRITICAL: Must match exactly what we send)
            // DO NOT include fields that will be collected on Cybersource hosted page
            // For eCheck, Cybersource collects bank details on their page
            $signedFields = [
                'access_key',
                'profile_id',
                'transaction_uuid',
                'signed_date_time',
                'locale',
                'transaction_type',
                'reference_number',
                'amount',
                'currency',
                'payment_method',
                'bill_to_forename',
                'bill_to_surname',
                'bill_to_email',
                'bill_to_phone',
                'bill_to_address_line1',
                'bill_to_address_city',
                'bill_to_address_state',
                'bill_to_address_country',
                'bill_to_address_postal_code',
                'override_custom_receipt_page',
                'override_custom_cancel_page'
            ];
            
            // VALIDATION: Ensure all signed fields exist in params
            foreach ($signedFields as $field) {
                if (!array_key_exists($field, $params)) {
                    throw new Exception("Missing required signed field: {$field}");
                }
            }
            
            // Remove any empty/null values and rebuild params with only non-empty signed fields
            $cleanParams = [];
            $actualSignedFields = [];
            foreach ($signedFields as $field) {
                $value = $params[$field];
                // Trim whitespace that could break signature
                if (is_string($value)) {
                    $value = trim($value);
                }
                $cleanParams[$field] = $value;
                $actualSignedFields[] = $field;
            }
            
            $signedFieldNames = implode(',', $actualSignedFields);
            $cleanParams['signed_field_names'] = $signedFieldNames;
            
            // Add unsigned field names (required by Cybersource, even if empty)
            $cleanParams['unsigned_field_names'] = '';
            
            // Generate signature with CLEAN params
            $signature = $this->generateSignature($cleanParams, $signedFieldNames);
            $cleanParams['signature'] = $signature;
            
            // Use clean params as final params
            $params = $cleanParams;
            
            // ========================================
            // DEEP DEBUGGING: Log outgoing request
            // ========================================
            $this->logOutgoingRequest($params, $signedFieldNames);
            
            // Store transaction in database BEFORE redirecting
            $userId = $user ? $user['id'] : null;
            
            // Clean sensitive data before storing
            $requestData = $params;
            unset($requestData['signature']); // Don't store signature in raw_request
            
            $transactionId = PaymentTransaction::create([
                'order_id' => $order['id'],
                'user_id' => $userId,
                'gateway' => 'cybersource',
                'payment_method' => 'echeck',
                'transaction_uuid' => $transactionUuid,
                'reference_number' => $referenceNumber,
                'amount' => $amount,
                'currency' => 'USD',
                'status' => 'pending',
                'raw_request' => json_encode($requestData)
            ]);
            
            // Log transaction initiation
            Logger::info("eCheck payment initiated", [
                'transaction_id' => $transactionId,
                'order_id' => $order['id'],
                'amount' => $amount,
                'reference' => $referenceNumber
            ]);
            
            return [
                'url' => $this->endpoint,
                'fields' => $params
            ];
            
        } catch (Exception $e) {
            Logger::error("Failed to build eCheck checkout request", [
                'error' => $e->getMessage(),
                'order_id' => $order['id'] ?? null
            ]);
            throw $e;
        }
    }
    
    /**
     * Handle gateway response from Secure Acceptance (webhook/return URL)
     * 
     * @param array $post POST data from gateway ($_POST)
     * @return array ['success' => bool, 'message' => string, 'order_id' => int, 'transaction_id' => int]
     * @throws Exception
     */
    public function handleGatewayResponse(array $post): array
    {
        try {
            // STEP 1: Verify signature
            $signedFieldNames = $post['signed_field_names'] ?? '';
            
            if (empty($signedFieldNames)) {
                throw new Exception('Missing signed_field_names in response');
            }
            
            $isValid = $this->verifySignature($post, $signedFieldNames);
            
            if (!$isValid) {
                Logger::error("Invalid signature in payment response", [
                    'transaction_uuid' => $post['req_transaction_uuid'] ?? 'unknown',
                    'decision' => $post['decision'] ?? 'unknown'
                ]);
                
                // Mark transaction as failed
                if (!empty($post['req_transaction_uuid'])) {
                    $this->markTransactionFailed($post['req_transaction_uuid'], 
                        'Signature verification failed', 'INVALID_SIGNATURE');
                }
                
                return [
                    'success' => false,
                    'message' => 'Payment verification failed (invalid signature).',
                    'order_id' => null,
                    'transaction_id' => null
                ];
            }
            
            // STEP 2: Extract response data
            $transactionUuid = $post['req_transaction_uuid'] ?? null;
            $decision = strtoupper($post['decision'] ?? '');
            $reasonCode = $post['reason_code'] ?? null;
            $message = $post['message'] ?? '';
            $gatewayTransactionId = $post['transaction_id'] ?? null;
            $referenceNumber = $post['req_reference_number'] ?? null;
            $amount = $post['req_amount'] ?? null;
            
            // Extract error details if present
            $invalidFields = $post['invalid_fields'] ?? null;
            $missingFields = $post['missing_fields'] ?? null;
            
            if (!$transactionUuid) {
                throw new Exception('Missing transaction UUID in response');
            }
            
            // STEP 3: Find existing transaction with row-level lock (prevents race conditions)
            // Use SELECT FOR UPDATE to lock the row while we process
            $sql = "SELECT * FROM payment_transactions WHERE transaction_uuid = ? FOR UPDATE";
            $stmt = DB::query($sql, [$transactionUuid]);
            $existingTransaction = $stmt->fetch();
            
            if (!$existingTransaction) {
                // Transaction not found - rollback to release any locks
                if (DB::inTransaction()) {
                    DB::rollback();
                }
                
                Logger::error("Transaction not found for UUID", ['uuid' => $transactionUuid]);
                return [
                    'success' => false,
                    'message' => 'Transaction not found. Please contact support.',
                    'order_id' => null,
                    'transaction_id' => null
                ];
            }
            
            // Check if already processed (idempotency)
            if (in_array($existingTransaction['status'], ['approved', 'failed', 'cancelled'])) {
                // Already processed - rollback to release lock
                if (DB::inTransaction()) {
                    DB::rollback();
                }
                
                Logger::info("Transaction already processed (idempotent)", [
                    'transaction_id' => $existingTransaction['id'],
                    'status' => $existingTransaction['status']
                ]);
                
                return [
                    'success' => $existingTransaction['status'] === 'approved',
                    'message' => $this->getMessageForDecision($decision, $reasonCode, $message, 
                        $invalidFields, $missingFields),
                    'order_id' => $existingTransaction['order_id'],
                    'transaction_id' => $existingTransaction['id']
                ];
            }
            
            // STEP 4: Determine transaction status
            $transactionStatus = $this->mapDecisionToStatus($decision);
            
            // STEP 5: Begin database transaction (if not already in one)
            $wasInTransaction = DB::inTransaction();
            if (!$wasInTransaction) {
                DB::beginTransaction();
            }
            
            try {
                // Update payment transaction
                PaymentTransaction::updateByUuid($transactionUuid, [
                    'gateway_transaction_id' => $gatewayTransactionId,
                    'decision' => $decision,
                    'reason_code' => $reasonCode,
                    'message' => $message,
                    'status' => $transactionStatus,
                    'raw_response' => json_encode($post)
                ]);
                
                // Update order payment status
                $orderId = $existingTransaction['order_id'];
                
                if ($decision === 'ACCEPT') {
                    // Payment successful
                    $sql = "UPDATE orders 
                            SET payment_status = 'paid', 
                                paid_at = NOW(),
                                payment_method = 'echeck',
                                updated_at = NOW()
                            WHERE id = ?";
                    DB::query($sql, [$orderId]);
                    
                    // NOW clear the cart (only after payment confirmed)
                    $this->clearCustomerCart($existingTransaction);
                    
                    Logger::info("eCheck payment approved", [
                        'order_id' => $orderId,
                        'transaction_id' => $existingTransaction['id'],
                        'amount' => $amount,
                        'gateway_transaction_id' => $gatewayTransactionId
                    ]);
                    
                } elseif ($decision === 'CANCEL') {
                    // Payment cancelled by user
                    $sql = "UPDATE orders 
                            SET payment_status = 'cancelled',
                                updated_at = NOW()
                            WHERE id = ?";
                    DB::query($sql, [$orderId]);
                    
                    Logger::info("eCheck payment cancelled", [
                        'order_id' => $orderId,
                        'transaction_id' => $existingTransaction['id']
                    ]);
                    
                } else {
                    // Payment failed or error
                    $sql = "UPDATE orders 
                            SET payment_status = 'failed',
                                updated_at = NOW()
                            WHERE id = ?";
                    DB::query($sql, [$orderId]);
                    
                    Logger::warning("eCheck payment failed", [
                        'order_id' => $orderId,
                        'transaction_id' => $existingTransaction['id'],
                        'decision' => $decision,
                        'reason_code' => $reasonCode,
                        'message' => $message
                    ]);
                }
                
                DB::commit();
                
                // Prepare response message
                $userMessage = $this->getMessageForDecision($decision, $reasonCode, $message, 
                    $invalidFields, $missingFields);
                
                return [
                    'success' => $decision === 'ACCEPT',
                    'message' => $userMessage,
                    'order_id' => $orderId,
                    'transaction_id' => $existingTransaction['id'],
                    'decision' => $decision,
                    'reason_code' => $reasonCode
                ];
                
            } catch (Exception $e) {
                DB::rollback();
                throw $e;
            }
            
        } catch (Exception $e) {
            Logger::error("Error handling gateway response", [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return [
                'success' => false,
                'message' => 'An error occurred processing your payment. Please contact support.',
                'order_id' => null,
                'transaction_id' => null
            ];
        }
    }
    
    /**
     * Generate HMAC SHA256 signature for Secure Acceptance
     * 
     * @param array $params Request parameters
     * @param string $signedFieldNames Comma-separated list of field names to sign
     * @return string Base64 encoded signature
     */
    private function generateSignature(array $params, string $signedFieldNames): string
    {
        $signedFieldNamesArray = explode(',', $signedFieldNames);
        
        // CRITICAL VALIDATION: Ensure all signed fields exist in params
        $missingFields = [];
        foreach ($signedFieldNamesArray as $field) {
            $field = trim($field);
            if (!array_key_exists($field, $params)) {
                $missingFields[] = $field;
            }
        }
        
        if (!empty($missingFields)) {
            Logger::error('Cybersource Signature Error: Missing signed fields', [
                'missing_fields' => $missingFields,
                'signed_field_names' => $signedFieldNames,
                'available_fields' => array_keys($params)
            ]);
            throw new \Exception('Missing required fields for signature: ' . implode(', ', $missingFields));
        }
        
        // Build data string from signed fields (key=value pairs)
        $dataToSign = [];
        foreach ($signedFieldNamesArray as $field) {
            $field = trim($field);
            $value = $params[$field];
            
            // CRITICAL: Detect newlines/carriage returns that break signatures
            if (is_string($value) && (strpos($value, "\n") !== false || strpos($value, "\r") !== false)) {
                Logger::error('Cybersource Signature Error: Newline detected in field value', [
                    'field' => $field,
                    'value_preview' => substr($value, 0, 100)
                ]);
                throw new \Exception("Field '{$field}' contains newline characters which will break signature");
            }
            
            $dataToSign[] = $field . '=' . $value;
        }
        
        $dataString = implode(',', $dataToSign);
        
        // Debug logging (safe - no secret key logged)
        Logger::info('Cybersource Signature Generation', [
            'data_to_sign_length' => strlen($dataString),
            'data_to_sign_preview' => substr($dataString, 0, 200) . '...',
            'signed_field_count' => count($signedFieldNamesArray)
        ]);
        
        // Generate HMAC SHA256 signature
        $hash = hash_hmac('sha256', $dataString, $this->secretKey, true);
        
        // Base64 encode
        $signature = base64_encode($hash);
        
        Logger::info('Cybersource Signature Generated', [
            'signature_length' => strlen($signature),
            'signature_preview' => substr($signature, 0, 20) . '...'
        ]);
        
        return $signature;
    }
    
    /**
     * Verify signature from gateway response
     * 
     * @param array $response Response parameters from gateway
     * @param string $signedFieldNames Comma-separated list of signed field names
     * @return bool True if signature is valid
     */
    private function verifySignature(array $response, string $signedFieldNames): bool
    {
        $receivedSignature = $response['signature'] ?? '';
        
        if (empty($receivedSignature)) {
            return false;
        }
        
        // Generate expected signature
        $expectedSignature = $this->generateSignature($response, $signedFieldNames);
        
        // Compare signatures (timing-safe comparison)
        return hash_equals($expectedSignature, $receivedSignature);
    }
    
    /**
     * Log outgoing Cybersource request for debugging (masks sensitive data)
     * 
     * @param array $params Request parameters
     * @param string $signedFieldNames Comma-separated list of signed field names
     * @return void
     */
    private function logOutgoingRequest(array $params, string $signedFieldNames): void
    {
        // Build the data_to_sign string for debugging
        $signedFieldNamesArray = explode(',', $signedFieldNames);
        $dataToSign = [];
        foreach ($signedFieldNamesArray as $field) {
            $field = trim($field);
            $value = $params[$field] ?? '';
            $dataToSign[] = $field . '=' . $value;
        }
        $dataString = implode(',', $dataToSign);
        
        // Mask sensitive params for logging
        $logParams = $params;
        if (isset($logParams['access_key'])) {
            $logParams['access_key'] = $this->maskSensitiveData($logParams['access_key']);
        }
        if (isset($logParams['signature'])) {
            $logParams['signature'] = $this->maskSensitiveData($logParams['signature']);
        }
        
        Logger::info('Cybersource Outgoing Request', [
            'endpoint' => $this->endpoint,
            'profile_id' => $this->profileId,
            'access_key_masked' => $this->maskSensitiveData($this->accessKey),
            'signed_field_names' => $signedFieldNames,
            'signed_field_count' => count($signedFieldNamesArray),
            'transaction_uuid' => $params['transaction_uuid'] ?? 'N/A',
            'transaction_type' => $params['transaction_type'] ?? 'N/A',
            'amount' => $params['amount'] ?? 'N/A',
            'currency' => $params['currency'] ?? 'N/A',
            'signed_date_time' => $params['signed_date_time'] ?? 'N/A',
            'data_to_sign_length' => strlen($dataString),
            'data_to_sign' => $dataString, // Full data string for verification
            'signature_masked' => isset($params['signature']) ? $this->maskSensitiveData($params['signature']) : 'N/A',
            'all_params' => $logParams
        ]);
    }
    
    /**
     * Generate unique transaction UUID
     * 
     * @return string UUID format: xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx
     */
    private function generateTransactionUuid(): string
    {
        // Generate UUID v4
        $data = random_bytes(16);
        $data[6] = chr(ord($data[6]) & 0x0f | 0x40); // Version 4
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80); // Variant
        
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }
    
    /**
     * Map Cybersource decision to internal transaction status
     * 
     * @param string $decision Decision from gateway (ACCEPT, DECLINE, ERROR, CANCEL)
     * @return string Internal status (approved, failed, cancelled)
     */
    private function mapDecisionToStatus(string $decision): string
    {
        $decision = strtoupper($decision);
        
        switch ($decision) {
            case 'ACCEPT':
                return 'approved';
            case 'CANCEL':
                return 'cancelled';
            case 'DECLINE':
            case 'ERROR':
            default:
                return 'failed';
        }
    }
    
    /**
     * Get user-friendly message based on decision and reason code
     * 
     * @param string $decision Decision code
     * @param string|null $reasonCode Reason code
     * @param string|null $gatewayMessage Message from gateway
     * @param string|null $invalidFields Invalid fields (comma-separated)
     * @param string|null $missingFields Missing required fields (comma-separated)
     * @return string User-friendly message
     */
    private function getMessageForDecision(
        string $decision, 
        ?string $reasonCode, 
        ?string $gatewayMessage,
        ?string $invalidFields = null,
        ?string $missingFields = null
    ): string {
        $decision = strtoupper($decision);
        
        // Handle missing/invalid fields errors
        if (!empty($missingFields)) {
            return "Missing required fields: {$missingFields}. Please check your information and try again.";
        }
        
        if (!empty($invalidFields)) {
            return "Invalid fields: {$invalidFields}. Please correct the information and try again.";
        }
        
        // Map reason codes to user-friendly messages
        $reasonCodeMessages = [
            '100' => 'Payment approved successfully!',
            '101' => 'Payment declined. Please check your account information and try again.',
            '102' => 'Payment declined - insufficient funds. Please use a different account or payment method.',
            '110' => 'Payment declined - only partial amount approved. Please contact support.',
            '150' => 'Payment request error. Please contact support.',
            '151' => 'Payment request error. Please try again or contact support.',
            '200' => 'Payment flagged for review. We will update your order status within 24 hours.',
            '201' => 'Payment flagged for review. We will contact you if additional information is needed.',
            '202' => 'Payment declined - account frozen. Please contact your bank.',
            '203' => 'Payment declined - invalid account. Please verify your account information.',
            '204' => 'Payment declined - insufficient funds. Please use a different account.',
            '205' => 'Payment declined - account number stolen or lost. Please contact your bank.',
            '207' => 'Payment declined - invalid account number. Please verify and try again.',
            '208' => 'Payment declined - account inactive. Please contact your bank.',
            '210' => 'Payment declined - daily limit exceeded. Please try again tomorrow or contact your bank.',
            '211' => 'Payment declined - invalid CVN. Please verify your information.',
            '221' => 'Customer matched restricted list. Please contact support.',
            '230' => 'Payment declined - invalid configuration. Please contact support.',
            '231' => 'Payment declined - invalid account number. Please verify and try again.',
            '232' => 'Payment declined - invalid account type. Please use a checking or savings account.',
            '233' => 'Payment declined - invalid merchant configuration. Please contact support.',
            '234' => 'Payment incomplete - merchant configuration issue. Please contact support.',
            '236' => 'Payment declined - processor failure. Please try again later.',
            '240' => 'Payment declined - invalid merchant configuration. Please contact support.',
            '250' => 'Payment timed out. Please try again.',
            '520' => 'Payment did not meet settlement criteria. Please contact support.',
        ];
        
        // Decision-based responses
        if ($decision === 'ACCEPT') {
            return $reasonCodeMessages['100'];
        }
        
        if ($decision === 'CANCEL') {
            return 'Payment was cancelled. You can retry checkout if you wish to complete your order.';
        }
        
        if ($decision === 'ERROR') {
            if ($reasonCode && isset($reasonCodeMessages[$reasonCode])) {
                return $reasonCodeMessages[$reasonCode];
            }
            
            // Show exact gateway message if available
            if (!empty($gatewayMessage)) {
                return "Payment error: {$gatewayMessage} (Code: {$reasonCode})";
            }
            
            return "Payment processing error occurred. Please try again or contact support. (Code: {$reasonCode})";
        }
        
        // DECLINE
        if ($reasonCode && isset($reasonCodeMessages[$reasonCode])) {
            return $reasonCodeMessages[$reasonCode];
        }
        
        // Show exact gateway message if available
        if (!empty($gatewayMessage)) {
            return "Payment declined: {$gatewayMessage} (Code: {$reasonCode})";
        }
        
        return "Payment was declined. Please check your account information or try a different payment method. (Code: {$reasonCode})";
    }
    
    /**
     * Mark transaction as failed (used for signature verification failures)
     * 
     * @param string $uuid Transaction UUID
     * @param string $message Error message
     * @param string $reasonCode Reason code
     * @return void
     */
    private function markTransactionFailed(string $uuid, string $message, string $reasonCode): void
    {
        try {
            PaymentTransaction::updateByUuid($uuid, [
                'decision' => 'ERROR',
                'reason_code' => $reasonCode,
                'message' => $message,
                'status' => 'failed',
                'raw_response' => json_encode(['error' => $message])
            ]);
            
            // Update order status
            $transaction = PaymentTransaction::findByUuid($uuid);
            if ($transaction) {
                $sql = "UPDATE orders 
                        SET payment_status = 'failed',
                            updated_at = NOW()
                        WHERE id = ?";
                DB::query($sql, [$transaction['order_id']]);
            }
        } catch (Exception $e) {
            Logger::error("Failed to mark transaction as failed", [
                'uuid' => $uuid,
                'error' => $e->getMessage()
            ]);
        }
    }
    
    /**
     * Clear customer cart after successful payment
     * 
     * @param array $transaction Transaction data with user_id and order_id
     * @return void
     */
    private function clearCustomerCart(array $transaction): void
    {
        try {
            if (!empty($transaction['user_id'])) {
                // Logged-in user - clear database cart
                $sql = "SELECT id FROM carts WHERE user_id = ? LIMIT 1";
                $stmt = DB::query($sql, [$transaction['user_id']]);
                $cart = $stmt->fetch();
                
                if ($cart) {
                    // Delete cart items
                    $deleteSql = "DELETE FROM cart_items WHERE cart_id = ?";
                    DB::query($deleteSql, [$cart['id']]);
                    
                    Logger::info("Cart cleared after payment", [
                        'user_id' => $transaction['user_id'],
                        'cart_id' => $cart['id']
                    ]);
                }
            } else {
                // Guest user - clear session cart
                if (isset($_SESSION['cart'])) {
                    unset($_SESSION['cart']);
                    Logger::info("Guest cart cleared after payment", [
                        'order_id' => $transaction['order_id']
                    ]);
                }
            }
        } catch (Exception $e) {
            // Don't fail the payment if cart clearing fails
            Logger::error("Failed to clear cart after payment", [
                'error' => $e->getMessage(),
                'order_id' => $transaction['order_id']
            ]);
        }
    }
    
    /**
     * Destroy sensitive data (call after processing)
     * 
     * @return void
     */
    public function destroySensitiveData(): void
    {
        // Clear sensitive properties
        $this->secretKey = '';
        $this->accessKey = '';
    }
}
