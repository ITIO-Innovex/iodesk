<?php
// Test script to check AI providers table
require_once('application/config/app-config.php');

// Database connection
$host = APP_DB_HOSTNAME;
$username = APP_DB_USERNAME;
$password = APP_DB_PASSWORD;
$database = APP_DB_NAME;
$prefix = APP_DB_PREFIX;

$mysqli = new mysqli($host, $username, $password, $database);

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

echo "<h2>AI Providers Table Test</h2>";

// Check if table exists
$table_name = $prefix . 'ai_providers';
$result = $mysqli->query("SHOW TABLES LIKE '$table_name'");

if ($result->num_rows == 0) {
    echo "<p style='color: red;'>Table '$table_name' does not exist. Creating it...</p>";
    
    // Create table
    $create_sql = "
    CREATE TABLE `$table_name` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `provider_name` varchar(255) NOT NULL,
      `provider_url` varchar(500) NOT NULL,
      `api_key` text NOT NULL,
      `status` tinyint(1) DEFAULT 1 COMMENT '1=Active, 0=Inactive',
      `created_at` datetime NOT NULL,
      `updated_at` datetime NOT NULL,
      PRIMARY KEY (`id`),
      KEY `idx_status` (`status`),
      KEY `idx_provider_name` (`provider_name`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ";
    
    if ($mysqli->query($create_sql)) {
        echo "<p style='color: green;'>Table created successfully!</p>";
        
        // Insert default provider
        $insert_sql = "INSERT INTO `$table_name` (`provider_name`, `provider_url`, `api_key`, `status`, `created_at`, `updated_at`) 
                       VALUES ('OpenAI', 'https://api.openai.com/v1/chat/completions', 'your-openai-api-key-here', 1, NOW(), NOW())";
        
        if ($mysqli->query($insert_sql)) {
            echo "<p style='color: green;'>Default provider inserted!</p>";
        } else {
            echo "<p style='color: red;'>Error inserting default provider: " . $mysqli->error . "</p>";
        }
    } else {
        echo "<p style='color: red;'>Error creating table: " . $mysqli->error . "</p>";
    }
} else {
    echo "<p style='color: green;'>Table '$table_name' exists!</p>";
    
    // Show table structure
    $desc_result = $mysqli->query("DESCRIBE `$table_name`");
    echo "<h3>Table Structure:</h3>";
    echo "<table border='1' style='border-collapse: collapse;'>";
    echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
    
    while ($row = $desc_result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row['Field'] . "</td>";
        echo "<td>" . $row['Type'] . "</td>";
        echo "<td>" . $row['Null'] . "</td>";
        echo "<td>" . $row['Key'] . "</td>";
        echo "<td>" . $row['Default'] . "</td>";
        echo "<td>" . $row['Extra'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    // Show existing records
    $data_result = $mysqli->query("SELECT * FROM `$table_name`");
    echo "<h3>Existing Records: " . $data_result->num_rows . "</h3>";
    
    if ($data_result->num_rows > 0) {
        echo "<table border='1' style='border-collapse: collapse;'>";
        echo "<tr><th>ID</th><th>Provider Name</th><th>URL</th><th>API Key</th><th>Status</th><th>Created</th></tr>";
        
        while ($row = $data_result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row['id'] . "</td>";
            echo "<td>" . $row['provider_name'] . "</td>";
            echo "<td>" . substr($row['provider_url'], 0, 50) . "...</td>";
            echo "<td>" . str_repeat('*', 20) . substr($row['api_key'], -4) . "</td>";
            echo "<td>" . ($row['status'] ? 'Active' : 'Inactive') . "</td>";
            echo "<td>" . $row['created_at'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
}

$mysqli->close();

echo "<br><p><a href='admin/ai_content_generator/manage_ai_provider'>Go to AI Provider Management</a></p>";
echo "<p><em>Delete this file after testing: test_ai_providers.php</em></p>";
?>
