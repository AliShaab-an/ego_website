<?php

require_once __DIR__ . '/../core/DB.php';

class Newsletter {
    
    public static function subscribe($name, $email, $userId = null) {
        try {
            // Validate input
            if (empty(trim($name))) {
                throw new Exception("Name is required.");
            }
            if (empty(trim($email))) {
                throw new Exception("Email is required.");
            }
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw new Exception("Please enter a valid email address.");
            }
            
            // Check if email already exists
            if (self::emailExists($email)) {
                throw new Exception("This email is already subscribed to our newsletter.");
            }
            
            $sql = "INSERT INTO newsletter_subscriptions (name, email, user_id, subscribed_at) 
                    VALUES (?, ?, ?, NOW())";
            
            DB::query($sql, [
                trim($name),
                trim($email),
                $userId
            ]);
            
            return DB::getConnection()->lastInsertId();
        } catch (Exception $e) {
            throw new Exception("Failed to subscribe: " . $e->getMessage());
        }
    }
    
    public static function emailExists($email) {
        try {
            $sql = "SELECT id FROM newsletter_subscriptions WHERE email = ? AND status = 'active'";
            $stmt = DB::query($sql, [$email]);
            return $stmt->fetch(PDO::FETCH_ASSOC) !== false;
        } catch (Exception $e) {
            throw new Exception("Failed to check email: " . $e->getMessage());
        }
    }
    
    public static function getAll($limit = 50, $offset = 0, $status = 'all') {
        try {
            $limit = (int)$limit;
            $offset = (int)$offset;
            
            $whereClause = "";
            $params = [];
            
            if ($status !== 'all') {
                $whereClause = "WHERE ns.status = ?";
                $params[] = $status;
            }
            
            $sql = "SELECT ns.*, u.username 
                    FROM newsletter_subscriptions ns
                    LEFT JOIN users u ON ns.user_id = u.id
                    $whereClause
                    ORDER BY ns.subscribed_at DESC 
                    LIMIT $limit OFFSET $offset";
                    
            $stmt = DB::query($sql, $params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            throw new Exception("Failed to fetch newsletter subscriptions: " . $e->getMessage());
        }
    }
    
    public static function getById($id) {
        try {
            $sql = "SELECT ns.*, u.username 
                    FROM newsletter_subscriptions ns
                    LEFT JOIN users u ON ns.user_id = u.id
                    WHERE ns.id = ?";
                    
            $stmt = DB::query($sql, [$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            throw new Exception("Failed to fetch newsletter subscription: " . $e->getMessage());
        }
    }
    
    public static function unsubscribe($id) {
        try {
            $sql = "UPDATE newsletter_subscriptions SET status = 'unsubscribed', updated_at = NOW() WHERE id = ?";
            DB::query($sql, [$id]);
            return true;
        } catch (Exception $e) {
            throw new Exception("Failed to unsubscribe: " . $e->getMessage());
        }
    }
    
    public static function resubscribe($id) {
        try {
            $sql = "UPDATE newsletter_subscriptions SET status = 'active', updated_at = NOW() WHERE id = ?";
            DB::query($sql, [$id]);
            return true;
        } catch (Exception $e) {
            throw new Exception("Failed to resubscribe: " . $e->getMessage());
        }
    }
    
    public static function countAll($status = 'all') {
        try {
            $whereClause = "";
            $params = [];
            
            if ($status !== 'all') {
                $whereClause = "WHERE status = ?";
                $params[] = $status;
            }
            
            $stmt = DB::query("SELECT COUNT(*) AS count FROM newsletter_subscriptions $whereClause", $params);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return (int)$row['count'];
        } catch (Exception $e) {
            throw new Exception("Failed to count newsletter subscriptions: " . $e->getMessage());
        }
    }
    
    public static function countActive() {
        return self::countAll('active');
    }
    
    public static function countUnsubscribed() {
        return self::countAll('unsubscribed');
    }
    
    public static function getAllForExport($status = 'active') {
        try {
            $whereClause = "";
            $params = [];
            
            if ($status !== 'all') {
                $whereClause = "WHERE ns.status = ?";
                $params[] = $status;
            }
            
            $sql = "SELECT ns.name, ns.email, ns.status, ns.subscribed_at, u.username as registered_user
                    FROM newsletter_subscriptions ns
                    LEFT JOIN users u ON ns.user_id = u.id
                    $whereClause
                    ORDER BY ns.subscribed_at DESC";
                    
            $stmt = DB::query($sql, $params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            throw new Exception("Failed to fetch newsletter data for export: " . $e->getMessage());
        }
    }
    
    public static function generateCSV($data) {
        $csv = "Name,Email,Status,Subscribed Date,Registered User\n";
        
        foreach ($data as $row) {
            $csv .= sprintf(
                '"%s","%s","%s","%s","%s"' . "\n",
                str_replace('"', '""', $row['name']),
                str_replace('"', '""', $row['email']),
                $row['status'],
                date('Y-m-d H:i:s', strtotime($row['subscribed_at'])),
                $row['registered_user'] ? 'Yes (' . str_replace('"', '""', $row['registered_user']) . ')' : 'No'
            );
        }
        
        return $csv;
    }
}