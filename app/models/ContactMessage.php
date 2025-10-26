<?php

require_once __DIR__ . '/../core/DB.php';

class ContactMessage {
    
    public static function create($name, $email, $message, $userId = null) {
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
            if (empty(trim($message))) {
                throw new Exception("Message is required.");
            }
            
            $sql = "INSERT INTO contact_messages (name, email, message, user_id, created_at) 
                    VALUES (?, ?, ?, ?, NOW())";
            
            DB::query($sql, [
                trim($name),
                trim($email),
                trim($message),
                $userId
            ]);
            
            return DB::getConnection()->lastInsertId();
        } catch (Exception $e) {
            throw new Exception("Failed to save contact message: " . $e->getMessage());
        }
    }
    
    public static function getAll($limit = 50, $offset = 0) {
        try {
            $limit = (int)$limit;
            $offset = (int)$offset;
            
            $sql = "SELECT cm.*, u.username 
                    FROM contact_messages cm
                    LEFT JOIN users u ON cm.user_id = u.id
                    ORDER BY cm.created_at DESC 
                    LIMIT $limit OFFSET $offset";
                    
            $stmt = DB::query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            throw new Exception("Failed to fetch contact messages: " . $e->getMessage());
        }
    }
    
    public static function getById($id) {
        try {
            $sql = "SELECT cm.*, u.username 
                    FROM contact_messages cm
                    LEFT JOIN users u ON cm.user_id = u.id
                    WHERE cm.id = ?";
                    
            $stmt = DB::query($sql, [$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            throw new Exception("Failed to fetch contact message: " . $e->getMessage());
        }
    }
    
    public static function markAsRead($id) {
        try {
            $sql = "UPDATE contact_messages SET is_read = 1 WHERE id = ?";
            DB::query($sql, [$id]);
            return true;
        } catch (Exception $e) {
            throw new Exception("Failed to mark message as read: " . $e->getMessage());
        }
    }
    
    public static function countAll() {
        try {
            $stmt = DB::query("SELECT COUNT(*) AS count FROM contact_messages");
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return (int)$row['count'];
        } catch (Exception $e) {
            throw new Exception("Failed to count contact messages: " . $e->getMessage());
        }
    }
    
    public static function countUnread() {
        try {
            $stmt = DB::query("SELECT COUNT(*) AS count FROM contact_messages WHERE is_read = 0");
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return (int)$row['count'];
        } catch (Exception $e) {
            throw new Exception("Failed to count unread messages: " . $e->getMessage());
        }
    }
    
    public static function countRead() {
        try {
            $stmt = DB::query("SELECT COUNT(*) AS count FROM contact_messages WHERE is_read = 1");
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return (int)$row['count'];
        } catch (Exception $e) {
            throw new Exception("Failed to count read messages: " . $e->getMessage());
        }
    }
    
    public static function getUnread($limit = 50, $offset = 0) {
        try {
            $limit = (int)$limit;
            $offset = (int)$offset;
            
            $sql = "SELECT cm.*, u.username 
                    FROM contact_messages cm
                    LEFT JOIN users u ON cm.user_id = u.id
                    WHERE cm.is_read = 0
                    ORDER BY cm.created_at DESC 
                    LIMIT $limit OFFSET $offset";
                    
            $stmt = DB::query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            throw new Exception("Failed to fetch unread contact messages: " . $e->getMessage());
        }
    }
    
    public static function getRead($limit = 50, $offset = 0) {
        try {
            $limit = (int)$limit;
            $offset = (int)$offset;
            
            $sql = "SELECT cm.*, u.username 
                    FROM contact_messages cm
                    LEFT JOIN users u ON cm.user_id = u.id
                    WHERE cm.is_read = 1
                    ORDER BY cm.created_at DESC 
                    LIMIT $limit OFFSET $offset";
                    
            $stmt = DB::query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            throw new Exception("Failed to fetch read contact messages: " . $e->getMessage());
        }
    }
}