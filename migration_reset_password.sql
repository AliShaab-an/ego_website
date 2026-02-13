-- Add password reset token columns to users table
-- Run this SQL in your database to add password reset functionality

ALTER TABLE `users` 
ADD COLUMN `reset_token` VARCHAR(64) NULL DEFAULT NULL AFTER `password`,
ADD COLUMN `reset_token_expiry` DATETIME NULL DEFAULT NULL AFTER `reset_token`;

-- Add index for faster token lookup
ALTER TABLE `users` 
ADD INDEX `idx_reset_token` (`reset_token`);
