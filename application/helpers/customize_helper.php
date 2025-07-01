<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Initialize customize tabs
 */
function app_init_customize_tabs()
{
    $CI = &get_instance();

    $CI->app_tabs->add_settings_tab('appearance', [
        'name'     => _l('customize_appearance'),
        'view'     => 'admin/customize/includes/appearance',
        'position' => 5,
        'icon'     => 'fa fa-palette',
    ]);
}

/**
 * Get customize option
 * @param  string $name
 * @param  mixed  $default
 * @return mixed
 */
function get_customize_option($name, $default = '')
{
    $value = get_option($name);
    
    if ($value === false || $value === null) {
        return $default;
    }
    
    return $value;
}

/**
 * Update customize option
 * @param  string $name
 * @param  mixed  $value
 * @return boolean
 */
function update_customize_option($name, $value)
{
    return update_option($name, $value);
}

/**
 * Delete customize option
 * @param  string $name
 * @return boolean
 */
function delete_customize_option($name)
{
    return delete_option($name);
}

/**
 * Check if customize option exists
 * @param  string $name
 * @return boolean
 */
function customize_option_exists($name)
{
    return option_exists($name);
}

/**
 * Get all customize options
 * @return array
 */
function get_all_customize_options()
{
    $CI = &get_instance();
    $CI->load->model('customize_model');
    
    return $CI->customize_model->get_all_custom_settings();
}

/**
 * Get customize color schemes
 * @return array
 */
function get_customize_color_schemes()
{
    $CI = &get_instance();
    $CI->load->model('customize_model');
    
    return $CI->customize_model->get_color_schemes();
}

/**
 * Get customize font options
 * @return array
 */
function get_customize_font_options()
{
    $CI = &get_instance();
    $CI->load->model('customize_model');
    
    return $CI->customize_model->get_font_options();
}

/**
 * Get customize layout options
 * @return array
 */
function get_customize_layout_options()
{
    $CI = &get_instance();
    $CI->load->model('customize_model');
    
    return $CI->customize_model->get_layout_options();
} 