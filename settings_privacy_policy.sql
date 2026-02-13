-- Add privacy policy title and terms of service title columns to the settings table
-- Run this SQL in your database to add these fields

-- Add privacy policy title column
ALTER TABLE `settings` 
ADD COLUMN IF NOT EXISTS `privacy_policy_title` VARCHAR(255) DEFAULT 'Privacy Policy' 
AFTER `privacy_policy`;

-- Add terms of service title column
ALTER TABLE `settings` 
ADD COLUMN IF NOT EXISTS `terms_of_service_title` VARCHAR(255) DEFAULT 'Terms & Conditions' 
AFTER `terms_conditions`;

-- Update the default values for existing row
UPDATE `settings` 
SET `privacy_policy_title` = 'Privacy Policy',
    `terms_of_service_title` = 'Terms & Conditions'
WHERE `privacy_policy_title` IS NULL OR `terms_of_service_title` IS NULL
LIMIT 1;
