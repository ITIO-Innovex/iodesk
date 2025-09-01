-- Add task reminder columns to tasks table
-- Run this SQL in phpMyAdmin or MySQL command line

ALTER TABLE `tbl_tasks` ADD `task_reminder` VARCHAR(20) DEFAULT 'none' AFTER `task_tags`;
ALTER TABLE `tbl_tasks` ADD `reminder_daily_time` TIME NULL AFTER `task_reminder`;
ALTER TABLE `tbl_tasks` ADD `reminder_on_date` VARCHAR(20) NULL AFTER `reminder_daily_time`;
ALTER TABLE `tbl_tasks` ADD `reminder_on_time` TIME NULL AFTER `reminder_on_date`;

-- Verify columns were added
SHOW COLUMNS FROM `tbl_tasks` LIKE '%reminder%';
