<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Prepares email template preview $data for the view
 * @param  string $template    template class name
 * @param  mixed $customer_id_or_email customer ID to fetch the primary contact email or email
 * @return array
 */
function prepare_mail_preview_data($template, $customer_id_or_email, $mailClassParams = [])
{
    $CI = &get_instance();

    if (is_numeric($customer_id_or_email)) {
        $contact = $CI->clients_model->get_contact(get_primary_contact_user_id($customer_id_or_email));
        $email   = $contact ? $contact->email : '';
    } else {
        $email = $customer_id_or_email;
    }

    $CI->load->model('emails_model');

    $data['template'] = $CI->app_mail_template->prepare($email, $template);
    $slug             = $CI->app_mail_template->get_default_property_value('slug', $template, $mailClassParams);

    $data['template_name'] = $slug;

    $template_result = $CI->emails_model->get(['slug' => $slug, 'language' => 'english'], 'row');

    $data['template_system_name'] = $template_result->name;
    $data['template_id']          = $template_result->emailtemplateid;

    $data['template_disabled'] = $template_result->active == 0;

    return $data;
}
/**
 * Parse email template with the merge fields
 * @param  mixed $template     template
 * @param  array  $merge_fields
 * @return object
 */
function parse_email_template($template, $merge_fields = [])
{
    $CI = & get_instance();
    if (!is_object($template) || $CI->input->post('template_name')) {
        $original_template = $template;

        if (!class_exists('emails_model', false)) {
            $CI->load->model('emails_model');
        }

        if ($CI->input->post('template_name')) {
            $template = $CI->input->post('template_name');
        }

        $template = $CI->emails_model->get(['slug' => $template], 'row');

        if ($CI->input->post('email_template_custom')) {
            $template->message = $CI->input->post('email_template_custom', false);
            // Replace the subject too
            $template->subject = $original_template->subject;
        }
    }

    $template = parse_email_template_merge_fields($template, $merge_fields);

    // Used in hooks eq for emails tracking
    $template->tmp_id = app_generate_hash();

    return hooks()->apply_filters('email_template_parsed', $template);
}

/**
 * This function will parse email template merge fields and replace with the corresponding merge fields passed before sending email
 * @param  object $template     template from database
 * @param  array $merge_fields available merge fields
 * @return object
 */
function parse_email_template_merge_fields($template, $merge_fields)
{
    $CI = &get_instance();

    if (!class_exists('other_merge_fields', false)) {
        $CI->load->library('merge_fields/other_merge_fields');
    }

    $merge_fields = array_merge($merge_fields, $CI->other_merge_fields->format());

    foreach ($merge_fields as $key => $val) {
        foreach (['message', 'fromname', 'subject'] as $section) {
            $template->{$section} = stripos($template->{$section}, $key) !== false
            ? str_replace($key, $val, $template->{$section})
            : str_replace($key, '', $template->{$section});
        }
    }

    return $template;
}

/**
 * Send mail template
 * @since  2.3.0
 * @return mixed
 */
function send_mail_template()
{
    $params = func_get_args();

    try {
        $mail_instance = mail_template(...$params);
        if (!$mail_instance) {
            $error_msg = 'Failed to create mail template instance';
            log_activity($error_msg);
            log_email_error($error_msg, $params);
            return false;
        }
        
        $result = $mail_instance->send();
        
        if (!$result) {
            $error_msg = 'send_mail_template() function: Email sending failed';
            $template_class = isset($params[0]) ? $params[0] : 'Unknown';
            
            // Handle recipient parameter - could be string, object, or other types
            $recipient = 'Unknown';
            if (isset($params[1])) {
                if (is_string($params[1])) {
                    $recipient = $params[1];
                } elseif (is_object($params[1])) {
                    // Try to get email from common object properties
                    if (isset($params[1]->email)) {
                        $recipient = $params[1]->email;
                    } elseif (isset($params[1]->name)) {
                        $recipient = $params[1]->name;
                    } elseif (isset($params[1]->id)) {
                        $recipient = 'ID: ' . $params[1]->id;
                    } else {
                        $recipient = get_class($params[1]) . ' object';
                    }
                } else {
                    $recipient = gettype($params[1]);
                }
            }
            
            $detailed_error = "Email sending failed - Template: {$template_class}, Recipient: {$recipient}";
            
            log_activity($error_msg);
            log_email_error($detailed_error, $params);
        }
        
        return $result;
    } catch (Exception $e) {
        $error_msg = 'send_mail_template() function: Exception occurred - ' . $e->getMessage();
        log_activity($error_msg);
        log_email_error($error_msg, $params, $e);
        return false;
    }
}

/**
 * Log email errors to dedicated log file with detailed information
 * @param string $error_message The error message
 * @param array $params The parameters passed to send_mail_template
 * @param Exception $exception Optional exception object
 */
function log_email_error($error_message, $params = [], $exception = null)
{
    $CI = &get_instance();
    
    // Create logs directory if it doesn't exist
    $log_dir = APPPATH . '../logs/';
    if (!is_dir($log_dir)) {
        mkdir($log_dir, 0755, true);
    }
    
    $log_file = $log_dir . 'email_errors_' . date('Y-m-d') . '.log';
    
    // Handle recipient parameter safely for logging
    $recipient_info = 'N/A';
    if (isset($params[1])) {
        if (is_string($params[1])) {
            $recipient_info = $params[1];
        } elseif (is_object($params[1])) {
            // Try to get meaningful info from object
            if (isset($params[1]->email)) {
                $recipient_info = $params[1]->email;
            } elseif (isset($params[1]->name)) {
                $recipient_info = $params[1]->name;
            } elseif (isset($params[1]->id)) {
                $recipient_info = 'ID: ' . $params[1]->id;
            } else {
                $recipient_info = get_class($params[1]) . ' object';
            }
        } else {
            $recipient_info = gettype($params[1]);
        }
    }

    $log_entry = [
        'timestamp' => date('Y-m-d H:i:s'),
        'error' => $error_message,
        'template_class' => isset($params[0]) ? $params[0] : 'N/A',
        'recipient' => $recipient_info,
        'parameters' => $params,
        'user_agent' => isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : 'N/A',
        'ip_address' => isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : 'N/A',
        'request_uri' => isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : 'N/A'
    ];
    
    if ($exception) {
        $log_entry['exception'] = [
            'message' => $exception->getMessage(),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'trace' => $exception->getTraceAsString()
        ];
    }
    
    // Format log entry
    $formatted_log = "[" . $log_entry['timestamp'] . "] EMAIL ERROR\n";
    $formatted_log .= "Error: " . $log_entry['error'] . "\n";
    $formatted_log .= "Template: " . $log_entry['template_class'] . "\n";
    $formatted_log .= "Recipient: " . $log_entry['recipient'] . "\n";
    $formatted_log .= "Parameters: " . json_encode($log_entry['parameters']) . "\n";
    $formatted_log .= "IP: " . $log_entry['ip_address'] . "\n";
    $formatted_log .= "URI: " . $log_entry['request_uri'] . "\n";
    
    if (isset($log_entry['exception'])) {
        $formatted_log .= "Exception File: " . $log_entry['exception']['file'] . "\n";
        $formatted_log .= "Exception Line: " . $log_entry['exception']['line'] . "\n";
        $formatted_log .= "Exception Message: " . $log_entry['exception']['message'] . "\n";
        $formatted_log .= "Stack Trace:\n" . $log_entry['exception']['trace'] . "\n";
    }
    
    $formatted_log .= str_repeat("-", 80) . "\n\n";
    
    // Write to log file
    file_put_contents($log_file, $formatted_log, FILE_APPEND | LOCK_EX);
    
    // Also log to CodeIgniter's log if available
    if (class_exists('CI_Log')) {
        log_message('error', 'Email Error: ' . $error_message);
    }
}

/**
 * Prepare mail template class
 * @param  string $class mail template class name
 * @return mixed
 */
function mail_template($class)
{
    $CI = &get_instance();

    $params = func_get_args();

    // First params is the $class param
    unset($params[0]);

    $params = array_values($params);

    $path = get_mail_template_path($class, $params);

    if (!file_exists($path)) {
        if (!defined('CRON')) {
            show_error('Mail Class Does Not Exists [' . $path . ']');
        } else {
            return false;
        }
    }

    // Include the mailable class
    if (!class_exists($class, false)) {
        include_once($path);
    }

    // Initialize the class and pass the params
    $instance = new $class(...$params);

    // Call the send method
    return $instance;
}

function get_mail_template_path($class, &$params)
{
    $CI  = &get_instance();
    $dir = APPPATH . 'libraries/mails/';

    // Check if second parameter is module and is activated so we can get the class from the module path
    // Also check if the first value is not equal to '/' e.q. when import is performed we set
    // for some values which are blank to "/"
    if (isset($params[0]) && is_string($params[0]) && $params[0] !== '/' && is_dir(module_dir_path($params[0]))) {
        $module = $CI->app_modules->get($params[0]);

        if ($module['activated'] === 1) {
            $dir = module_libs_path($params[0]) . 'mails/';
        }

        unset($params[0]);
        $params = array_values($params);
    }

    return $dir . ucfirst($class) . '.php';
}
/**
 * Create new email template
 * @param  string  $subject the predefined email template subject
 * @param  string  $message the predefined email template message
 * @param  string  $type    for what feature this email template is related e.q. invoice|ticket
 * @param  string  $name    the email template name which user see in Setup->Email Template, this is used for easier email template recognition
 * @param  string  $slug    unique email template slug
 * @param  integer $active  whether by default this email template is active
 * @return mixed
 */
function create_email_template($subject, $message, $type, $name, $slug, $active = 1)
{
    if (total_rows('emailtemplates', ['slug' => $slug]) > 0) {
        return false;
    }

    $data['subject']   = $subject;
    $data['message']   = $message;
    $data['type']      = $type;
    $data['name']      = $name;
    $data['slug']      = $slug;
    $data['language']  = 'english';
    $data['active']    = $active;
    $data['plaintext'] = 0;
    $data['fromname'] = '{companyname} | CRM';

    $CI                = &get_instance();
    $CI->load->model('emails_model');

    return $CI->emails_model->add_template($data);
}

/**
 * Check whether an email template is active based on given slug
 *
 * @since 2.7.0
 *
 * @param  string  $slug
 *
 * @return boolean
 */
function is_email_template_active($slug)
{
    return total_rows(db_prefix() . 'emailtemplates', ['slug' => $slug, 'active' => 1]) > 0;
}