<?php
require_once __DIR__ . '/../config/path.php';
require_once MODELS . 'Settings.php';

class SettingsController {

    private $uploadDir;

    public function __construct() {
        $this->uploadDir = __DIR__ . '/../../public/admin/uploads/settings/';
        if (!is_dir($this->uploadDir)) {
            mkdir($this->uploadDir, 0777, true);
        }
    }

    /**
     * Get all settings
     */
    public function getSettings() {
        try {
            $settings = Settings::getAll();
            
            return [
                'status' => 'success',
                'data' => $settings
            ];
        } catch (Exception $e) {
            error_log("Error getting settings: " . $e->getMessage());
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }

    /**
     * Save all settings
     */
    public function saveSettings() {
        try {
            $data = [];

            // Handle text inputs
            $textFields = [
                'website_name', 'website_url', 'contact_email', 'support_email', 
                'phone_number', 'working_hours', 'address', 'google_maps_link', 
                'whatsapp_number', 'instagram_url', 'facebook_url', 'tiktok_url', 
                'twitter_url', 'linkedin_url', 'youtube_url', 'meta_title', 
                'meta_keywords', 'currency', 'primary_font', 'secondary_font',
                'cod_instructions', 'wish_money_number', 'wish_money_name', 
                'wish_money_instructions', 'bank_name', 'bank_account', 
                'bank_account_name', 'bank_instructions', 'omt_name', 
                'omt_instructions', 'smtp_host', 'smtp_username', 'smtp_password',
                'smtp_from_name', 'smtp_from_email', 'google_analytics_id',
                'gtm_id', 'meta_pixel_id', 'tiktok_pixel_id', 'maintenance_message',
                'recaptcha_site_key', 'recaptcha_secret_key', 'about_us',
                'return_policy', 'shipping_policy', 'privacy_policy', 'terms_conditions'
            ];

            foreach ($textFields as $field) {
                if (isset($_POST[$field])) {
                    $data[$field] = trim($_POST[$field]);
                }
            }

            // Handle textarea inputs
            $textareaFields = ['meta_description'];
            foreach ($textareaFields as $field) {
                if (isset($_POST[$field])) {
                    $data[$field] = trim($_POST[$field]);
                }
            }

            // Handle colors
            $colorFields = ['primary_color', 'secondary_color', 'accent_color'];
            foreach ($colorFields as $field) {
                if (isset($_POST[$field])) {
                    $data[$field] = $_POST[$field];
                }
            }

            // Handle checkboxes
            $checkboxFields = [
                'require_payment_proof', 'enable_cod', 'enable_wish_money', 
                'enable_bank_transfer', 'enable_omt', 'enable_smtp', 
                'enable_maintenance', 'enable_recaptcha'
            ];

            foreach ($checkboxFields as $field) {
                $data[$field] = isset($_POST[$field]) ? 1 : 0;
            }

            // Handle numeric fields
            $numericFields = ['smtp_port'];
            foreach ($numericFields as $field) {
                if (isset($_POST[$field])) {
                    $data[$field] = intval($_POST[$field]);
                }
            }

            // Handle SMTP encryption
            if (isset($_POST['smtp_encryption'])) {
                $data['smtp_encryption'] = $_POST['smtp_encryption'];
            }

            // Handle file uploads
            $fileFieldMappings = [
                'logo_file' => 'logo',
                'logo_light_file' => 'logo_light',
                'logo_dark_file' => 'logo_dark',
                'favicon_file' => 'favicon',
                'homepage_bg' => 'homepage_bg',
                'shop_bg' => 'shop_bg',
                'contact_bg' => 'contact_bg',
                'login_bg' => 'login_bg',
                'signup_bg' => 'signup_bg',
                'og_image' => 'og_image'
            ];

            foreach ($fileFieldMappings as $formField => $dbField) {
                if (isset($_FILES[$formField]) && $_FILES[$formField]['error'] === UPLOAD_ERR_OK) {
                    $uploadedPath = $this->uploadFile($_FILES[$formField]);
                    if ($uploadedPath) {
                        $data[$dbField] = $uploadedPath;
                    }
                }
            }

            // Save settings
            if (Settings::update($data)) {
                return [
                    'status' => 'success',
                    'message' => 'Settings saved successfully!'
                ];
            } else {
                return [
                    'status' => 'error',
                    'message' => 'Failed to save settings'
                ];
            }
        } catch (Exception $e) {
            error_log("Error saving settings: " . $e->getMessage());
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }

    /**
     * Upload a file and return the relative path
     */
    private function uploadFile($file) {
        try {
            $filename = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', basename($file['name']));
            $targetPath = $this->uploadDir . $filename;

            if (move_uploaded_file($file['tmp_name'], $targetPath)) {
                // Return relative path from public folder
                return 'admin/uploads/settings/' . $filename;
            }

            return null;
        } catch (Exception $e) {
            error_log("Error uploading file: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Get a specific setting
     */
    public function getSetting() {
        try {
            $key = $_GET['key'] ?? null;

            if (!$key) {
                return ['status' => 'error', 'message' => 'Setting key is required'];
            }

            $value = Settings::get($key);

            return [
                'status' => 'success',
                'data' => $value
            ];
        } catch (Exception $e) {
            error_log("Error getting setting: " . $e->getMessage());
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }

    /**
     * Save a single setting
     */
    public function saveSetting() {
        try {
            $key = $_POST['key'] ?? null;
            $value = $_POST['value'] ?? null;

            if (!$key) {
                return ['status' => 'error', 'message' => 'Setting key is required'];
            }

            if (Settings::save($key, $value)) {
                return [
                    'status' => 'success',
                    'message' => 'Setting saved successfully!'
                ];
            } else {
                return [
                    'status' => 'error',
                    'message' => 'Failed to save setting'
                ];
            }
        } catch (Exception $e) {
            error_log("Error saving setting: " . $e->getMessage());
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }

    /**
     * Validate SMTP settings
     */
    public function validateSmtp() {
        try {
            $host = $_POST['smtp_host'] ?? null;
            $port = $_POST['smtp_port'] ?? null;
            $username = $_POST['smtp_username'] ?? null;
            $password = $_POST['smtp_password'] ?? null;

            if (!$host || !$port || !$username || !$password) {
                return ['status' => 'error', 'message' => 'Missing required SMTP fields'];
            }

            // Attempt connection (basic validation)
            if (@fsockopen($host, intval($port), $errno, $errstr, 5)) {
                return [
                    'status' => 'success',
                    'message' => 'SMTP connection successful!'
                ];
            } else {
                return [
                    'status' => 'error',
                    'message' => "SMTP connection failed: {$errstr}"
                ];
            }
        } catch (Exception $e) {
            error_log("Error validating SMTP: " . $e->getMessage());
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }
}
