<?php

require_once __DIR__ . '/../core/DB.php';

class Settings {

    /**
     * Get all settings
     */
    public static function getAll() {
        try {
            $stmt = DB::query("SELECT * FROM website_settings");
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            if (!$result) {
                error_log("Settings query returned no results");
                return [];
            }
            
            $settings = [];
            foreach ($result as $row) {
                $settings[$row['setting_key']] = $row['setting_value'];
            }
            
            return $settings;
        } catch (Exception $e) {
            error_log("Error fetching all settings: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Get a specific setting by key
     */
    public static function get($key, $default = null) {
        try {
            $stmt = DB::query("SELECT setting_value FROM website_settings WHERE setting_key = ?", [$key]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($result) {
                return $result['setting_value'];
            }
            
            return $default;
        } catch (Exception $e) {
            error_log("Error fetching setting '{$key}': " . $e->getMessage());
            return $default;
        }
    }

    /**
     * Save a single setting
     */
    public static function save($key, $value) {
        try {
            // Check if setting exists
            $checkStmt = DB::query("SELECT id FROM website_settings WHERE setting_key = ?", [$key]);
            $exists = $checkStmt->fetch(PDO::FETCH_ASSOC);
            
            if ($exists) {
                // Update existing setting
                DB::query("UPDATE website_settings SET setting_value = ?, updated_at = NOW() WHERE setting_key = ?", [$value, $key]);
            } else {
                // Insert new setting
                DB::query("INSERT INTO website_settings (setting_key, setting_value) VALUES (?, ?)", [$key, $value]);
            }
            
            return true;
        } catch (Exception $e) {
            error_log("Error saving setting '{$key}': " . $e->getMessage());
            return false;
        }
    }

    /**
     * Save multiple settings at once
     */
    public static function saveMultiple($settings) {
        try {
            foreach ($settings as $key => $value) {
                self::save($key, $value);
            }
            return true;
        } catch (Exception $e) {
            error_log("Error saving multiple settings: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Delete a setting by key
     */
    public static function delete($key) {
        try {
            DB::query("DELETE FROM website_settings WHERE setting_key = ?", [$key]);
            return true;
        } catch (Exception $e) {
            error_log("Error deleting setting '{$key}': " . $e->getMessage());
            return false;
        }
    }

    /**
     * Check if a setting key exists
     */
    public static function exists($key) {
        try {
            $stmt = DB::query("SELECT id FROM website_settings WHERE setting_key = ?", [$key]);
            return $stmt->fetch(PDO::FETCH_ASSOC) !== false;
        } catch (Exception $e) {
            error_log("Error checking setting '{$key}': " . $e->getMessage());
            return false;
        }
    }
}
