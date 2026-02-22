<?php


    class Settings {

        public static function getAll() {
            try {
                $stmt = DB::query("SELECT * FROM settings LIMIT 1");
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if (!$result) {
                    // Return empty array if no settings found - will use defaults
                    return [];
                }
                
                return $result;
            } catch (Exception $e) {
                // If settings table doesn't exist or query fails, return empty array
                error_log("Settings::getAll() error: " . $e->getMessage());
                return [];
            }
        }

        /**
         * Get a single setting by key
         */
        public static function get($key, $default = null) {
            $settings = self::getAll();
            return $settings[$key] ?? $default;
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
                // Use proper escaping for field names
                $fields[] = "`" . str_replace("`", "``", $key) . "` = ?";
                $values[] = $value;
            }
            
            if (empty($fields)) {
                return true;
            }
            
            $values[] = $id;
            $query = "UPDATE settings SET " . implode(", ", $fields) . ", updated_at = NOW() WHERE id = ?";
            
            DB::query($query, $values);
            
            return true;
        } catch (Exception $e) {
            error_log("Settings update error: " . $e->getMessage());
            error_log("Query: " . ($query ?? 'N/A'));
            error_log("Data: " . print_r($data, true));
            return false;
        }
    }

    /**
     * Save a single setting
     */
    public static function save($key, $value) {
            $data = [$key => $value];
            return self::update($data);
        }

        /**
         * Save multiple settings at once
         */
        public static function saveMultiple($settings) {
            return self::update($settings);
        }

        /**
         * Get settings by array of keys
         */
        public static function getMultiple($keys) {
            $settings = self::getAll();
            $result = [];
            
            foreach ($keys as $key) {
                $result[$key] = $settings[$key] ?? null;
            }
            
            return $result;
        }
    }
    
