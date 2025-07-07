<?php
require_once "application/config/db.php";

// Start logging
file_put_contents(__DIR__ . '/telegram_webhook.log', "\n=== Webhook Triggered at " . date('Y-m-d H:i:s') . " ===\n", FILE_APPEND);

// Step 1: Get Bot Name
$bot_name = $_GET['bot'] ?? '';
file_put_contents(__DIR__ . '/telegram_webhook.log', "Bot Name: $bot_name\n", FILE_APPEND);
if (empty($bot_name)) {
    file_put_contents(__DIR__ . '/telegram_webhook.log', "ERROR: Bot name is missing\n", FILE_APPEND);
    die("Bot name is required.");
}

// Step 2: Fetch token from DB
$sqlStmt = "SELECT id, telegram_token FROM `it_crm_telegram_bot` WHERE telegram_name = '$bot_name'";
$res = mysqli_query($conn, $sqlStmt);
if (!$res) {
    file_put_contents(__DIR__ . '/telegram_webhook.log', "DB Error (fetch token): " . mysqli_error($conn) . "\n", FILE_APPEND);
}
if (mysqli_num_rows($res) > 0) {
    $row = mysqli_fetch_assoc($res);
    $token = $row['telegram_token'];
    $botId = $row['id'];
    file_put_contents(__DIR__ . '/telegram_webhook.log', "Token Found: $token (Bot ID: $botId)\n", FILE_APPEND);
} else {
    $token = "default-token-here";
    $botId = 0;
    file_put_contents(__DIR__ . '/telegram_webhook.log', "Default Token Used: $token\n", FILE_APPEND);
}

// Step 3: Read Telegram message
$input = file_get_contents('php://input');
file_put_contents(__DIR__ . '/telegram_webhook.log', "Raw Input:\n$input\n", FILE_APPEND);
$web_data = json_decode($input);
file_put_contents(__DIR__ . '/telegram_webhook.log', "Decoded JSON:\n" . print_r($web_data, true) . "\n", FILE_APPEND);

// Step 4: Process message
if (isset($web_data->message->chat->id)) {
    $chat_id = $web_data->message->chat->id;
    $username = $web_data->message->chat->username ?? '';
    $name = $web_data->message->chat->first_name ?? '';
    $last_name = $web_data->message->chat->last_name ?? '';
    $name .= $last_name ? " $last_name" : '';
    $text = $web_data->message->text ?? '';

    if ($text === '/start') {
        $msg = "Hi $name,\nHow can I help you?";
        $url = "https://api.telegram.org/bot$token/sendMessage?text=" . urlencode($msg) . "&chat_id=$chat_id";
        file_put_contents(__DIR__ . '/telegram_webhook.log', "Sending /start message: $url\n", FILE_APPEND);
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_exec($ch);
        curl_close($ch);
    } else {
        // Step 5: Check if lead already exists
        $sqlStmt = "SELECT * FROM `it_crm_leads` WHERE `client_id` = '$chat_id'";
        $res = mysqli_query($conn, $sqlStmt);
        if (!$res) {
            file_put_contents(__DIR__ . '/telegram_webhook.log', "DB Error (lead check): " . mysqli_error($conn) . "\n", FILE_APPEND);
        }

        if (mysqli_num_rows($res) > 0) {
            // Log existing lead message to tbltelegram
            $row = mysqli_fetch_assoc($res);
            $lead_id = $row['id'];
            $photoArrayDecoded = json_decode($input, true);
            $imageUrl = '';
            if (isset($photoArrayDecoded['message']['photo'])) {
                $photos = $photoArrayDecoded['message']['photo'];
                usort($photos, fn($a, $b) => $b['file_size'] <=> $a['file_size']);
                $fileId = $photos[0]['file_id'];
                $getFileUrl = "https://api.telegram.org/bot$token/getFile?file_id=$fileId";
                $fileResponse = file_get_contents($getFileUrl);
                $fileResult = json_decode($fileResponse, true);
                if ($fileResult['ok']) {
                    $imageUrl = "https://api.telegram.org/file/bot$token/" . $fileResult['result']['file_path'];
                }
            }

            $sqlStmt = "INSERT INTO `tbltelegram` (`lead_id`, `chat_id`, `message`, `msg_type`, `timestamp`, `staff_id`, `json_detail`, `file_path`) 
                        VALUES ('$lead_id', '$chat_id', '$text', '2', NOW(), 0, '$input', '$imageUrl')";
            $res = mysqli_query($conn, $sqlStmt);
            if (!$res) {
                file_put_contents(__DIR__ . '/telegram_webhook.log', "DB Error (insert tbltelegram): " . mysqli_error($conn) . "\n", FILE_APPEND);
            } else {
                file_put_contents(__DIR__ . '/telegram_webhook.log', "Message inserted into tbltelegram.\n", FILE_APPEND);
            }

        } else {
            // Step 6: Insert new lead
            $sqlLeadInsert = "INSERT INTO `it_crm_leads` (`name`, `dateadded`, `description`, `client_id`, `SkypeInfo`, `source`, `status`, `telegram_bot_id`) 
                VALUES ('$name', NOW(), '$text', '$chat_id', '$username', 4, 2, '$botId')";
            file_put_contents(__DIR__ . '/telegram_webhook.log', "Attempting lead insert:\n$sqlLeadInsert\n", FILE_APPEND);

            if (mysqli_query($conn, $sqlLeadInsert)) {
                file_put_contents(__DIR__ . '/telegram_webhook.log', "New lead inserted successfully.\n", FILE_APPEND);

                // Step 7: Add notification
                $sqlNotif = "INSERT INTO `it_crm_notifications` (`isread`, `isread_inline`, `date`, `description`, `fromuserid`, `fromclientid`, `from_fullname`, `touserid`) 
                            VALUES (0, 0, NOW(), 'New Lead via Telegram', 0, '$chat_id', '$name', 1)";
                if (mysqli_query($conn, $sqlNotif)) {
                    file_put_contents(__DIR__ . '/telegram_webhook.log', "Notification inserted.\n", FILE_APPEND);
                } else {
                    file_put_contents(__DIR__ . '/telegram_webhook.log', "DB Error (insert notification): " . mysqli_error($conn) . "\n", FILE_APPEND);
                }
            } else {
                file_put_contents(__DIR__ . '/telegram_webhook.log', "DB Error (insert lead): " . mysqli_error($conn) . "\n", FILE_APPEND);
            }
        }
    }
} else {
    file_put_contents(__DIR__ . '/telegram_webhook.log', "No valid chat_id found in message.\n", FILE_APPEND);
}

mysqli_close($conn);
file_put_contents(__DIR__ . '/telegram_webhook.log', "=== End of Webhook ===\n", FILE_APPEND);
?>
