<?php
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
            $settings = Settings::getAll();
            
            return [
                'status' => 'success',
                'data' => $settings
            ];
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
                        try {
                            $uploadedPath = $this->uploadFile($_FILES[$formField]);
                            if ($uploadedPath) {
                                $data[$dbField] = $uploadedPath;
                            }
                        } catch (Exception $e) {
                            error_log("File upload error for {$formField}: " . $e->getMessage());
                            // Continue with other uploads even if one fails
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
                        'message' => 'Failed to save settings. Please check the error log for details.'
                    ];
                }
            } catch (Exception $e) {
                error_log("Settings save error: " . $e->getMessage());
                return [
                    'status' => 'error',
                    'message' => 'An error occurred while saving settings: ' . $e->getMessage()
                ];
            }
        }

        /**
         * Upload a file and return the relative path
         */
        private function uploadFile($file) {
            try {
                $outputDir = $this->uploadDir;
                
                // Use ImageService to process and resize the image
                $saved = ImageService::processUpload($file, $outputDir, [600, 1200], 82);
                
                // Use the 1200px version, or fallback to the largest available
                $filename = $saved[1200] ?? end($saved);
                
                // Return relative path from public folder
                return 'admin/uploads/settings/' . $filename;
            } catch (Exception $e) {
                error_log("Image upload error in SettingsController: " . $e->getMessage());
                throw new Exception("Failed to process image: " . $e->getMessage());
            }
        }

        /**
         * Get a specific setting
         */
        public function getSetting() {
            $key = $_GET['key'] ?? null;

            if (!$key) {
                return ['status' => 'error', 'message' => 'Setting key is required'];
            }

            $value = Settings::get($key);

            return [
                'status' => 'success',
                'data' => $value
            ];
        }

        /**
         * Save a single setting
         */
        public function saveSetting() {
            $key = $_POST['key'] ?? null;
            $value = $_POST['value'] ?? null;

            if (!$key) {
                return ['status' => 'error', 'message' => 'Setting key is required'];
            }

            if (Settings::save($key, $value)) {
                SettingsHelper::forgetCache();
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
        }

        /**
         * Validate SMTP settings
         */
        public function validateSmtp() {
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
        }
    }
