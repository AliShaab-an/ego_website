<?php

require_once __DIR__ . '/../core/DB.php';

class Newsletter {
    
    public static function subscribe($name, $email) {
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
            
            $sql = "INSERT INTO newsletter_subscribers (name, email, created_at) 
                    VALUES (?, ?, NOW())";
            
            DB::query($sql, [
                trim($name),
                trim($email)
            ]);
            
            return DB::getConnection()->lastInsertId();
        } catch (Exception $e) {
            throw new Exception("Failed to subscribe: " . $e->getMessage());
        }
    }
    
    public static function emailExists($email) {
        try {
            $sql = "SELECT id FROM newsletter_subscribers WHERE email = ? AND is_active = 1";
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
            
            if ($status === 'active') {
                $whereClause = "WHERE is_active = 1";
            } elseif ($status === 'inactive') {
                $whereClause = "WHERE is_active = 0";
            }
            
            $sql = "SELECT * FROM newsletter_subscribers 
                    $whereClause
                    ORDER BY created_at DESC 
                    LIMIT $limit OFFSET $offset";
                    
            $stmt = DB::query($sql, $params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            throw new Exception("Failed to fetch newsletter subscribers: " . $e->getMessage());
        }
    }
    
    public static function getById($id) {
        try {
            $sql = "SELECT * FROM newsletter_subscribers WHERE id = ?";
            $stmt = DB::query($sql, [$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            throw new Exception("Failed to fetch newsletter subscriber: " . $e->getMessage());
        }
    }
    
    public static function activate($id) {
        try {
            $sql = "UPDATE newsletter_subscribers SET is_active = 1 WHERE id = ?";
            DB::query($sql, [$id]);
            return true;
        } catch (Exception $e) {
            throw new Exception("Failed to activate subscriber: " . $e->getMessage());
        }
    }
    
    public static function deactivate($id) {
        try {
            $sql = "UPDATE newsletter_subscribers SET is_active = 0 WHERE id = ?";
            DB::query($sql, [$id]);
            return true;
        } catch (Exception $e) {
            throw new Exception("Failed to deactivate subscriber: " . $e->getMessage());
        }
    }
    
    public static function delete($id) {
        try {
            $sql = "DELETE FROM newsletter_subscribers WHERE id = ?";
            DB::query($sql, [$id]);
            return true;
        } catch (Exception $e) {
            throw new Exception("Failed to delete subscriber: " . $e->getMessage());
        }
    }
    
    public static function countAll($status = 'all') {
        try {
            $whereClause = "";
            $params = [];
            
            if ($status === 'active') {
                $whereClause = "WHERE is_active = 1";
            } elseif ($status === 'inactive') {
                $whereClause = "WHERE is_active = 0";
            }
            
            $stmt = DB::query("SELECT COUNT(*) AS count FROM newsletter_subscribers $whereClause", $params);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return (int)$row['count'];
        } catch (Exception $e) {
            throw new Exception("Failed to count newsletter subscribers: " . $e->getMessage());
        }
    }
    
    public static function countActive() {
        return self::countAll('active');
    }
    
    public static function countInactive() {
        return self::countAll('inactive');
    }
    
    public static function getAllForExport($status = 'all') {
        try {
            $whereClause = "";
            $params = [];
            
            if ($status === 'active') {
                $whereClause = "WHERE is_active = 1";
            } elseif ($status === 'inactive') {
                $whereClause = "WHERE is_active = 0";
            }
            
            $sql = "SELECT name, email, is_active, created_at
                    FROM newsletter_subscribers 
                    $whereClause
                    ORDER BY created_at DESC";
                    
            $stmt = DB::query($sql, $params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            throw new Exception("Failed to fetch newsletter data for export: " . $e->getMessage());
        }
    }
    
    public static function generateCSV($data) {
        $csv = "Name,Email,Status,Subscribed Date\n";
        
        foreach ($data as $row) {
            $csv .= sprintf(
                '"%s","%s","%s","%s"' . "\n",
                str_replace('"', '""', $row['name']),
                str_replace('"', '""', $row['email']),
                $row['is_active'] ? 'Active' : 'Inactive',
                date('Y-m-d H:i:s', strtotime($row['created_at']))
            );
        }
        
        return $csv;
    }
}