<?php
/**
 * Test script to verify enhanced email error logging functionality
 * This script tests the new file-based error logging in send_mail_template() function
 */

// Include the CodeIgniter bootstrap
require_once('index.php');

// Get CodeIgniter instance
$CI = &get_instance();

echo "<h2>Testing Enhanced Email Error Logging</h2>\n";

// Test 1: Test with invalid email template (should create log file with detailed error)
echo "<h3>Test 1: Invalid Email Template</h3>\n";
$result1 = send_mail_template('NonExistentTemplate', 'test@example.com');
echo "Result: " . ($result1 ? 'Success' : 'Failed') . "<br>\n";

// Test 2: Test with invalid email address (should log validation error)
echo "<h3>Test 2: Invalid Email Address</h3>\n";
$CI->load->model('emails_model');
$templates = $CI->emails_model->get();
if (!empty($templates)) {
    $template_slug = $templates[0]->slug;
    echo "Using template: " . $template_slug . "<br>\n";
    
    // Try to send to invalid email
    $result2 = send_mail_template($template_slug, 'invalid-email-address');
    echo "Result: " . ($result2 ? 'Success' : 'Failed') . "<br>\n";
} else {
    echo "No email templates found in database<br>\n";
}

// Test 3: Test exception handling
echo "<h3>Test 3: Exception Handling</h3>\n";
try {
    // This should trigger an exception
    $result3 = send_mail_template(null, null);
    echo "Result: " . ($result3 ? 'Success' : 'Failed') . "<br>\n";
} catch (Exception $e) {
    echo "Exception caught: " . $e->getMessage() . "<br>\n";
}

// Check if log file was created
$log_file = APPPATH . '../logs/email_errors_' . date('Y-m-d') . '.log';
echo "<h3>Log File Status</h3>\n";
if (file_exists($log_file)) {
    echo "✅ Log file created: " . $log_file . "<br>\n";
    echo "File size: " . filesize($log_file) . " bytes<br>\n";
    echo "Last modified: " . date('Y-m-d H:i:s', filemtime($log_file)) . "<br>\n";
    
    // Show last few lines of log file
    echo "<h4>Recent Log Entries:</h4>\n";
    echo "<pre style='background: #f5f5f5; padding: 10px; border: 1px solid #ddd; max-height: 300px; overflow-y: auto;'>";
    $log_content = file_get_contents($log_file);
    $lines = explode("\n", $log_content);
    $recent_lines = array_slice($lines, -50); // Show last 50 lines
    echo htmlspecialchars(implode("\n", $recent_lines));
    echo "</pre>";
} else {
    echo "❌ Log file not found<br>\n";
}

echo "<h3>Test Complete</h3>\n";
echo "Enhanced email error logging has been tested. Check the log file for detailed error information.<br>\n";
echo "Log location: <code>" . $log_file . "</code><br>\n";
?>
