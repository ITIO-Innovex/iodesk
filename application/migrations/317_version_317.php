<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Version_317 extends App_module_migration
{
    public function up()
    {
        $CI = &get_instance();
        
        // Add reminder columns to tasks table
        if (!$CI->db->field_exists('task_reminder', db_prefix() . 'tasks')) {
            $CI->db->query('ALTER TABLE `' . db_prefix() . 'tasks` ADD `task_reminder` VARCHAR(20) DEFAULT "none" AFTER `task_tags`');
        }
        
        if (!$CI->db->field_exists('reminder_daily_time', db_prefix() . 'tasks')) {
            $CI->db->query('ALTER TABLE `' . db_prefix() . 'tasks` ADD `reminder_daily_time` TIME NULL AFTER `task_reminder`');
        }
        
        if (!$CI->db->field_exists('reminder_on_date', db_prefix() . 'tasks')) {
            $CI->db->query('ALTER TABLE `' . db_prefix() . 'tasks` ADD `reminder_on_date` VARCHAR(20) NULL AFTER `reminder_daily_time`');
        }
        
        if (!$CI->db->field_exists('reminder_on_time', db_prefix() . 'tasks')) {
            $CI->db->query('ALTER TABLE `' . db_prefix() . 'tasks` ADD `reminder_on_time` TIME NULL AFTER `reminder_on_date`');
        }
    }

    public function down()
    {
        $CI = &get_instance();
        
        // Remove reminder columns from tasks table
        if ($CI->db->field_exists('task_reminder', db_prefix() . 'tasks')) {
            $CI->db->query('ALTER TABLE `' . db_prefix() . 'tasks` DROP COLUMN `task_reminder`');
        }
        
        if ($CI->db->field_exists('reminder_daily_time', db_prefix() . 'tasks')) {
            $CI->db->query('ALTER TABLE `' . db_prefix() . 'tasks` DROP COLUMN `reminder_daily_time`');
        }
        
        if ($CI->db->field_exists('reminder_on_date', db_prefix() . 'tasks')) {
            $CI->db->query('ALTER TABLE `' . db_prefix() . 'tasks` DROP COLUMN `reminder_on_date`');
        }
        
        if ($CI->db->field_exists('reminder_on_time', db_prefix() . 'tasks')) {
            $CI->db->query('ALTER TABLE `' . db_prefix() . 'tasks` DROP COLUMN `reminder_on_time`');
        }
    }
}
