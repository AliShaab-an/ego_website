<?php

/**
 * PaymentTransaction Model
 * 
 * Manages payment transaction records for all payment gateways (Cybersource, etc.)
 * 
 * USAGE:
 * 
 * // Create new transaction record
 * $transactionId = PaymentTransaction::create([
 *     'order_id' => 123,
 *     'user_id' => 45,
 *     'gateway' => 'cybersource',
 *     'payment_method' => 'echeck',
 *     'transaction_uuid' => 'uuid-1234-5678',
 *     'reference_number' => 'ORD-000123',
 *     'amount' => 99.99,
 *     'currency' => 'USD',
 *     'status' => 'pending',
 *     'raw_request' => json_encode($requestData)
 * ]);
 * 
 * // Update transaction with gateway response
 * PaymentTransaction::updateWithResponse($transactionId, [
 *     'decision' => 'ACCEPT',
 *     'reason_code' => '100',
 *     'gateway_transaction_id' => 'txn-9876-5432',
 *     'message' => 'Success',
 *     'status' => 'approved',
 *     'raw_response' => json_encode($responseData)
 * ]);
 * 
 * // Find by transaction UUID
 * $transaction = PaymentTransaction::findByUuid('uuid-1234-5678');
 * 
 * // Get all transactions for an order
 * $transactions = PaymentTransaction::getByOrderId(123);
 */
class PaymentTransaction
{
    /**
     * Create a new payment transaction record
     * 
     * @param array $data Transaction data
     * @return int Transaction ID
     * @throws Exception
     */
    public static function create(array $data): int
    {
        $requiredFields = ['order_id', 'gateway', 'payment_method', 'transaction_uuid', 
                          'reference_number', 'amount', 'currency', 'status'];
        
        foreach ($requiredFields as $field) {
            if (!isset($data[$field])) {
                throw new Exception("Missing required field: {$field}");
            }
        }
        
        $sql = "INSERT INTO payment_transactions (
                    order_id, user_id, gateway, payment_method, transaction_uuid,
                    gateway_transaction_id, reference_number, amount, currency,
                    decision, reason_code, message, status, raw_request, raw_response,
                    created_at, updated_at
                ) VALUES (
                    ?, ?, ?, ?, ?,
                    ?, ?, ?, ?,
                    ?, ?, ?, ?, ?, ?,
                    NOW(), NOW()
                )";
        
        $params = [
            $data['order_id'],
            $data['user_id'] ?? null,
            $data['gateway'],
            $data['payment_method'],
            $data['transaction_uuid'],
            $data['gateway_transaction_id'] ?? null,
            $data['reference_number'],
            $data['amount'],
            $data['currency'],
            $data['decision'] ?? null,
            $data['reason_code'] ?? null,
            $data['message'] ?? null,
            $data['status'],
            $data['raw_request'] ?? null,
            $data['raw_response'] ?? null
        ];
        
        DB::query($sql, $params);
        
        return (int) DB::getConnection()->lastInsertId();
    }
    
    /**
     * Update transaction with gateway response
     * 
     * @param int $transactionId Transaction ID
     * @param array $data Response data
     * @return bool Success
     * @throws Exception
     */
    public static function updateWithResponse(int $transactionId, array $data): bool
    {
        $sql = "UPDATE payment_transactions 
                SET gateway_transaction_id = COALESCE(?, gateway_transaction_id),
                    decision = ?,
                    reason_code = ?,
                    message = ?,
                    status = ?,
                    raw_response = ?,
                    updated_at = NOW()
                WHERE id = ?";
        
        $params = [
            $data['gateway_transaction_id'] ?? null,
            $data['decision'] ?? null,
            $data['reason_code'] ?? null,
            $data['message'] ?? null,
            $data['status'],
            $data['raw_response'] ?? null,
            $transactionId
        ];
        
        DB::query($sql, $params);
        
        return true;
    }
    
    /**
     * Update transaction by UUID (used in webhook processing)
     * 
     * @param string $uuid Transaction UUID
     * @param array $data Response data
     * @return bool Success
     * @throws Exception
     */
    public static function updateByUuid(string $uuid, array $data): bool
    {
        $sql = "UPDATE payment_transactions 
                SET gateway_transaction_id = COALESCE(?, gateway_transaction_id),
                    decision = ?,
                    reason_code = ?,
                    message = ?,
                    status = ?,
                    raw_response = ?,
                    updated_at = NOW()
                WHERE transaction_uuid = ?";
        
        $params = [
            $data['gateway_transaction_id'] ?? null,
            $data['decision'] ?? null,
            $data['reason_code'] ?? null,
            $data['message'] ?? null,
            $data['status'],
            $data['raw_response'] ?? null,
            $uuid
        ];
        
        $stmt = DB::query($sql, $params);
        
        return $stmt->rowCount() > 0;
    }
    
    /**
     * Find transaction by UUID
     * 
     * @param string $uuid Transaction UUID
     * @return array|null Transaction data or null if not found
     */
    public static function findByUuid(string $uuid): ?array
    {
        $sql = "SELECT * FROM payment_transactions WHERE transaction_uuid = ? LIMIT 1";
        
        $stmt = DB::query($sql, [$uuid]);
        $result = $stmt->fetch();
        
        return $result ?: null;
    }
    
    /**
     * Find transaction by gateway transaction ID
     * 
     * @param string $gatewayTransactionId Gateway transaction ID
     * @return array|null Transaction data or null if not found
     */
    public static function findByGatewayTransactionId(string $gatewayTransactionId): ?array
    {
        $sql = "SELECT * FROM payment_transactions 
                WHERE gateway_transaction_id = ? 
                LIMIT 1";
        
        $stmt = DB::query($sql, [$gatewayTransactionId]);
        $result = $stmt->fetch();
        
        return $result ?: null;
    }
    
    /**
     * Get all transactions for an order
     * 
     * @param int $orderId Order ID
     * @return array List of transactions
     */
    public static function getByOrderId(int $orderId): array
    {
        $sql = "SELECT * FROM payment_transactions 
                WHERE order_id = ? 
                ORDER BY created_at DESC";
        
        $stmt = DB::query($sql, [$orderId]);
        
        return $stmt->fetchAll();
    }
    
    /**
     * Get transaction by ID
     * 
     * @param int $id Transaction ID
     * @return array|null Transaction data or null if not found
     */
    public static function getById(int $id): ?array
    {
        $sql = "SELECT * FROM payment_transactions WHERE id = ? LIMIT 1";
        
        $stmt = DB::query($sql, [$id]);
        $result = $stmt->fetch();
        
        return $result ?: null;
    }
    
    /**
     * Get all transactions with pagination and filters
     * 
     * @param int $limit Results per page
     * @param int $offset Offset for pagination
     * @param array $filters Optional filters (status, gateway, payment_method)
     * @return array List of transactions
     */
    public static function getAll(int $limit = 20, int $offset = 0, array $filters = []): array
    {
        $conditions = [];
        $params = [];
        
        if (!empty($filters['status'])) {
            $conditions[] = "status = ?";
            $params[] = $filters['status'];
        }
        
        if (!empty($filters['gateway'])) {
            $conditions[] = "gateway = ?";
            $params[] = $filters['gateway'];
        }
        
        if (!empty($filters['payment_method'])) {
            $conditions[] = "payment_method = ?";
            $params[] = $filters['payment_method'];
        }
        
        if (!empty($filters['decision'])) {
            $conditions[] = "decision = ?";
            $params[] = $filters['decision'];
        }
        
        $whereClause = !empty($conditions) ? "WHERE " . implode(" AND ", $conditions) : "";
        
        $sql = "SELECT pt.*, o.order_number, o.total_amount as order_total
                FROM payment_transactions pt
                LEFT JOIN orders o ON pt.order_id = o.id
                {$whereClause}
                ORDER BY pt.created_at DESC
                LIMIT ? OFFSET ?";
        
        $params[] = $limit;
        $params[] = $offset;
        
        $stmt = DB::query($sql, $params);
        
        return $stmt->fetchAll();
    }
    
    /**
     * Count total transactions with optional filters
     * 
     * @param array $filters Optional filters (status, gateway, payment_method)
     * @return int Total count
     */
    public static function countAll(array $filters = []): int
    {
        $conditions = [];
        $params = [];
        
        if (!empty($filters['status'])) {
            $conditions[] = "status = ?";
            $params[] = $filters['status'];
        }
        
        if (!empty($filters['gateway'])) {
            $conditions[] = "gateway = ?";
            $params[] = $filters['gateway'];
        }
        
        if (!empty($filters['payment_method'])) {
            $conditions[] = "payment_method = ?";
            $params[] = $filters['payment_method'];
        }
        
        if (!empty($filters['decision'])) {
            $conditions[] = "decision = ?";
            $params[] = $filters['decision'];
        }
        
        $whereClause = !empty($conditions) ? "WHERE " . implode(" AND ", $conditions) : "";
        
        $sql = "SELECT COUNT(*) as count FROM payment_transactions {$whereClause}";
        
        $stmt = DB::query($sql, $params);
        $result = $stmt->fetch();
        
        return (int) $result['count'];
    }
    
    /**
     * Check if transaction UUID already exists (for idempotency)
     * 
     * @param string $uuid Transaction UUID
     * @return bool True if exists
     */
    public static function exists(string $uuid): bool
    {
        $sql = "SELECT COUNT(*) as count FROM payment_transactions WHERE transaction_uuid = ?";
        
        $stmt = DB::query($sql, [$uuid]);
        $result = $stmt->fetch();
        
        return (int) $result['count'] > 0;
    }
    
    /**
     * Get transaction statistics
     * 
     * @return array Statistics data
     */
    public static function getStatistics(): array
    {
        $sql = "SELECT 
                    COUNT(*) as total_transactions,
                    COUNT(CASE WHEN status = 'approved' THEN 1 END) as approved_count,
                    COUNT(CASE WHEN status = 'failed' THEN 1 END) as failed_count,
                    COUNT(CASE WHEN status = 'pending' THEN 1 END) as pending_count,
                    COUNT(CASE WHEN status = 'cancelled' THEN 1 END) as cancelled_count,
                    SUM(CASE WHEN status = 'approved' THEN amount ELSE 0 END) as total_approved_amount,
                    AVG(CASE WHEN status = 'approved' THEN amount ELSE NULL END) as avg_transaction_amount,
                    COUNT(CASE WHEN decision = 'ACCEPT' THEN 1 END) as accept_count,
                    COUNT(CASE WHEN decision = 'DECLINE' THEN 1 END) as decline_count,
                    COUNT(CASE WHEN decision = 'ERROR' THEN 1 END) as error_count
                FROM payment_transactions";
        
        $stmt = DB::query($sql);
        
        return $stmt->fetch();
    }
    
    /**
     * Clean sensitive data from raw request/response
     * 
     * @param string $jsonData JSON encoded data
     * @return string Cleaned JSON data
     */
    public static function cleanSensitiveData(string $jsonData): string
    {
        $data = json_decode($jsonData, true);
        
        if (!is_array($data)) {
            return $jsonData;
        }
        
        // List of sensitive fields to mask
        $sensitiveFields = [
            'secret_key',
            'account_number',
            'routing_number',
            'card_number',
            'cvv',
            'cvn',
            'password',
            'pin'
        ];
        
        foreach ($sensitiveFields as $field) {
            if (isset($data[$field])) {
                $data[$field] = '***REDACTED***';
            }
        }
        
        return json_encode($data);
    }
}
