<?php

require_once __DIR__ . '/../core/DB.php';

class Settings {

    public static function getAll() {
        try {
            $stmt = DB::query("SELECT * FROM settings LIMIT 1");
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$result) {
                throw new Exception("No settings found in database");
            }
            
            return $result;
        } catch (PDOException $e) {
            throw new Exception("Failed to fetch settings: " . $e->getMessage());
        }
    }

    /**
     * Get a single setting by key
     */
    public static function get($key, $default = null) {
        try {
            $settings = self::getAll();
            return $settings[$key] ?? $default;
        } catch (Exception $e) {
            return $default;
        }
    }

    public static function update($data) {
        try {
            // Get ID of the settings row
            $stmt = DB::query("SELECT id FROM settings LIMIT 1");
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$result) {
                // Create default settings row if it doesn't exist
                $insertParams = [
                    $data['website_name'] ?? 'Ego Clothing',
                    $data['website_url'] ?? 'https://ego-luxury.com'
                ];
                DB::query("INSERT INTO settings (website_name, website_url) VALUES (?, ?)", $insertParams);
                $result = ['id' => DB::getConnection()->lastInsertId()];
            }
            
            $id = $result['id'];
            
            // Build dynamic UPDATE query
            $fields = [];
            $values = [];
            
            foreach ($data as $key => $value) {
                // Skip id and timestamps
                if (in_array($key, ['id', 'created_at', 'updated_at'])) {
                    continue;
                }
                $fields[] = "`{$key}` = ?";
                $values[] = $value;
            }
            
            if (empty($fields)) {
                return true;
            }
            
            $values[] = $id;
            $query = "UPDATE settings SET " . implode(", ", $fields) . ", updated_at = NOW() WHERE id = ?";
            DB::query($query, $values);
            
            return true;
        } catch (PDOException $e) {
            throw new Exception("Failed to update settings: " . $e->getMessage());
        }
    }

    /**
     * Save a single setting
     */
    public static function save($key, $value) {
        try {
            $data = [$key => $value];
            return self::update($data);
        } catch (Exception $e) {
            throw new Exception("Failed to save setting '{$key}': " . $e->getMessage());
        }
    }

    /**
     * Save multiple settings at once
     */
    public static function saveMultiple($settings) {
        try {
            return self::update($settings);
        } catch (Exception $e) {
            throw new Exception("Failed to save multiple settings: " . $e->getMessage());
        }
    }

    /**
     * Get settings by array of keys
     */
    public static function getMultiple($keys) {
        try {
            $settings = self::getAll();
            $result = [];
            
            foreach ($keys as $key) {
                $result[$key] = $settings[$key] ?? null;
            }
            
            return $result;
        } catch (Exception $e) {
            throw new Exception("Failed to fetch multiple settings: " . $e->getMessage());
        }
    }
}

