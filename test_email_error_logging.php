<?php
/**
 * Test script to verify email error logging functionality
 * This script tests the enhanced error logging in send_mail_template() function
 */

// Include the CodeIgniter bootstrap
require_once('index.php');

// Get CodeIgniter instance
$CI = &get_instance();

echo "<h2>Testing Email Error Logging</h2>\n";

// Test 1: Test with invalid email template (should log template not found error)
echo "<h3>Test 1: Invalid Email Template</h3>\n";
$result1 = send_mail_template('NonExistentTemplate', 'test@example.com');
echo "Result: " . ($result1 ? 'Success' : 'Failed') . "<br>\n";

// Test 2: Test with invalid email address (should log validation error)
echo "<h3>Test 2: Invalid Email Address</h3>\n";
// First, let's find a valid template to use
$CI->load->model('emails_model');
$templates = $CI->emails_model->get();
if (!empty($templates)) {
    $template_slug = $templates[0]->slug;
    echo "Using template: " . $template_slug . "<br>\n";
    
    // Try to send to invalid email
    $result2 = send_mail_template($template_slug, 'invalid-email');
    echo "Result: " . ($result2 ? 'Success' : 'Failed') . "<br>\n";
} else {
    echo "No email templates found in database<br>\n";
}

// Test 3: Test with disabled template (if we can find one)
echo "<h3>Test 3: Disabled Email Template</h3>\n";
$disabled_template = $CI->emails_model->get(['active' => 0]);
if (!empty($disabled_template)) {
    $result3 = send_mail_template($disabled_template[0]->slug, 'test@example.com');
    echo "Result: " . ($result3 ? 'Success' : 'Failed') . "<br>\n";
} else {
    echo "No disabled templates found<br>\n";
}

echo "<h3>Check Activity Log</h3>\n";
echo "Please check the activity log in your CRM admin panel to see the detailed error messages.<br>\n";
echo "The errors should include specific details about why the email failed to send.<br>\n";

echo "<h3>Test Complete</h3>\n";
echo "All error scenarios have been tested. Check your logs for detailed error information.<br>\n";
?>
