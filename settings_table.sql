-- Settings Table for Ego Website
-- Create comprehensive website settings table

CREATE TABLE `settings` (
  `id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  
  -- GENERAL SETTINGS
  `website_name` VARCHAR(255) DEFAULT 'Ego Clothing',
  `website_url` VARCHAR(255) DEFAULT 'https://ego-clothing.com',
  `contact_email` VARCHAR(255) DEFAULT NULL,
  `support_email` VARCHAR(255) DEFAULT NULL,
  `phone_number` VARCHAR(20) DEFAULT NULL,
  `working_hours` LONGTEXT DEFAULT NULL,
  
  -- BRANDING SETTINGS
  `logo` VARCHAR(500) DEFAULT NULL,
  `logo_light` VARCHAR(500) DEFAULT NULL,
  `logo_dark` VARCHAR(500) DEFAULT NULL,
  `favicon` VARCHAR(500) DEFAULT NULL,
  `primary_color` VARCHAR(7) DEFAULT '#b7926f',
  `secondary_color` VARCHAR(7) DEFAULT '#9e7e59',
  `accent_color` VARCHAR(7) DEFAULT '#88663d',
  `primary_font` VARCHAR(100) DEFAULT NULL,
  `secondary_font` VARCHAR(100) DEFAULT NULL,
  
  -- BRANDING - PAGE BACKGROUNDS
  `homepage_bg` VARCHAR(500) DEFAULT NULL,
  `shop_bg` VARCHAR(500) DEFAULT NULL,
  `contact_bg` VARCHAR(500) DEFAULT NULL,
  `login_bg` VARCHAR(500) DEFAULT NULL,
  `signup_bg` VARCHAR(500) DEFAULT NULL,
  
  -- CONTACT & LOCATION
  `address` LONGTEXT DEFAULT NULL,
  `google_maps_link` VARCHAR(500) DEFAULT NULL,
  `whatsapp_number` VARCHAR(20) DEFAULT NULL,
  
  -- SOCIAL LINKS
  `instagram_url` VARCHAR(500) DEFAULT NULL,
  `facebook_url` VARCHAR(500) DEFAULT NULL,
  `tiktok_url` VARCHAR(500) DEFAULT NULL,
  `twitter_url` VARCHAR(500) DEFAULT NULL,
  `linkedin_url` VARCHAR(500) DEFAULT NULL,
  `youtube_url` VARCHAR(500) DEFAULT NULL,
  
  -- SEO SETTINGS
  `meta_title` VARCHAR(255) DEFAULT NULL,
  `meta_description` LONGTEXT DEFAULT NULL,
  `meta_keywords` LONGTEXT DEFAULT NULL,
  `og_image` VARCHAR(500) DEFAULT NULL,
  
  -- PAYMENT SETTINGS
  `currency` VARCHAR(10) DEFAULT 'USD',
  `require_payment_proof` BOOLEAN DEFAULT FALSE,
  
  -- Payment Methods - COD
  `enable_cod` BOOLEAN DEFAULT TRUE,
  `cod_instructions` LONGTEXT DEFAULT NULL,
  
  -- Payment Methods - Wish Money
  `enable_wish_money` BOOLEAN DEFAULT FALSE,
  `wish_money_number` VARCHAR(100) DEFAULT NULL,
  `wish_money_name` VARCHAR(255) DEFAULT NULL,
  `wish_money_instructions` LONGTEXT DEFAULT NULL,
  
  -- Payment Methods - Bank Transfer
  `enable_bank_transfer` BOOLEAN DEFAULT FALSE,
  `bank_name` VARCHAR(255) DEFAULT NULL,
  `bank_account` VARCHAR(100) DEFAULT NULL,
  `bank_account_name` VARCHAR(255) DEFAULT NULL,
  `bank_instructions` LONGTEXT DEFAULT NULL,
  
  -- Payment Methods - OMT/Western Union
  `enable_omt` BOOLEAN DEFAULT FALSE,
  `omt_name` VARCHAR(255) DEFAULT NULL,
  `omt_instructions` LONGTEXT DEFAULT NULL,
  
  -- POLICIES
  `about_us` LONGTEXT DEFAULT NULL,
  `return_policy` LONGTEXT DEFAULT NULL,
  `shipping_policy` LONGTEXT DEFAULT NULL,
  `privacy_policy` LONGTEXT DEFAULT NULL,
  `terms_conditions` LONGTEXT DEFAULT NULL,
  
  -- EMAIL/SMTP SETTINGS
  `enable_smtp` BOOLEAN DEFAULT FALSE,
  `smtp_host` VARCHAR(255) DEFAULT NULL,
  `smtp_port` INT(5) DEFAULT 587,
  `smtp_username` VARCHAR(255) DEFAULT NULL,
  `smtp_password` VARCHAR(255) DEFAULT NULL,
  `smtp_encryption` ENUM('none', 'tls', 'ssl') DEFAULT 'tls',
  `smtp_from_name` VARCHAR(255) DEFAULT NULL,
  `smtp_from_email` VARCHAR(255) DEFAULT NULL,
  
  -- ANALYTICS & TRACKING
  `google_analytics_id` VARCHAR(50) DEFAULT NULL,
  `gtm_id` VARCHAR(50) DEFAULT NULL,
  `meta_pixel_id` VARCHAR(50) DEFAULT NULL,
  `tiktok_pixel_id` VARCHAR(50) DEFAULT NULL,
  
  -- SECURITY & MAINTENANCE
  `enable_maintenance` BOOLEAN DEFAULT FALSE,
  `maintenance_message` LONGTEXT DEFAULT NULL,
  `enable_recaptcha` BOOLEAN DEFAULT FALSE,
  `recaptcha_site_key` VARCHAR(255) DEFAULT NULL,
  `recaptcha_secret_key` VARCHAR(255) DEFAULT NULL,
  
  -- TIMESTAMPS
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  
  INDEX `idx_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Insert default settings row
INSERT INTO `settings` (
  `website_name`, `website_url`, `primary_color`, `secondary_color`, `accent_color`, 
  `currency`, `enable_cod`, `smtp_port`, `smtp_encryption`
) VALUES (
  'Ego Clothing', 'https://ego-clothing.com', '#b7926f', '#9e7e59', '#88663d',
  'USD', TRUE, 587, 'tls'
);
