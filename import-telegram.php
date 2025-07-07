<?php
require_once "application/config/db.php";

// Log start of webhook hit
file_put_contents(__DIR__ . '/telegram_webhook.log', "\n=== Webhook Triggered at " . date('Y-m-d H:i:s') . " ===\n", FILE_APPEND);

// Fetch Token from the database
$bot_name = $_GET['bot'] ?? '';
file_put_contents(__DIR__ . '/telegram_webhook.log', "Bot Name: $bot_name\n", FILE_APPEND);

if (empty($bot_name)) {
    file_put_contents(__DIR__ . '/telegram_webhook.log', "Error: Bot name not provided.\n", FILE_APPEND);
    die("Bot name is required.");
}

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
    $token = "7750960478:AAHs_kjrNFODTpGA-J3xSzK6vDHxZOXKHSY";
    file_put_contents(__DIR__ . '/telegram_webhook.log', "Default Token Used: $token\n", FILE_APPEND);
}

$input = file_get_contents('php://input');
file_put_contents(__DIR__ . '/telegram_webhook.log', "Raw Input:\n$input\n", FILE_APPEND);

$web_data = json_decode($input);
file_put_contents(__DIR__ . '/telegram_webhook.log', "Decoded JSON:\n" . print_r($web_data, true) . "\n", FILE_APPEND);

if (isset($web_data->message->chat->id) && ($web_data->message->chat->id)) {
    echo json_encode($web_data);
    $chat_id = $web_data->message->chat->id;
    $username = $web_data->message->chat->username;
    $name = $web_data->message->chat->first_name;
    $text = $web_data->message->text;

    if (isset($web_data->message->chat->last_name)) {
        $name .= " " . $web_data->message->chat->last_name;
    }

    if ($text == '/start') {
        $msg = "Hi $name,\nHow can I help you?";
        $text = urlencode($msg);
        $url = "https://api.telegram.org/bot$token/sendMessage?text=$text&chat_id=$chat_id";
        file_put_contents(__DIR__ . '/telegram_webhook.log', "Sending /start message to $chat_id\nURL: $url\n", FILE_APPEND);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        $response = curl_exec($ch);
    } else {
        $chat_id = trim($chat_id);
        $sqlStmt = "SELECT * FROM `it_crm_leads` WHERE `client_id` = '$chat_id'";
        $res = mysqli_query($conn, $sqlStmt);

        if (!$res) {
            file_put_contents(__DIR__ . '/telegram_webhook.log', "DB Error (lead check): " . mysqli_error($conn) . "\n", FILE_APPEND);
        }

        if (mysqli_num_rows($res) > 0) {
            $row = mysqli_fetch_assoc($res);
            $lead_id = $row['id'];
            $photoArrayDecoded = json_decode($input, true);
            $imageUrl = '';

            if (isset($photoArrayDecoded['message']['photo']) && is_array($photoArrayDecoded['message']['photo']) && count($photoArrayDecoded['message']['photo']) > 0) {
                $photos = $photoArrayDecoded['message']['photo'];
                usort($photos, function ($a, $b) {
                    return $b['file_size'] <=> $a['file_size'];
                });
                $largestPhoto = $photos[0];
                $largestPhotofileId = $largestPhoto['file_id'];
                $getFileUrl = "https://api.telegram.org/bot$token/getFile?file_id=$largestPhotofileId";
                $fileResponse = file_get_contents($getFileUrl);
                $fileResult = json_decode($fileResponse, true);

                if ($fileResult['ok']) {
                    $filePath = $fileResult['result']['file_path'];
                    $imageUrl = "https://api.telegram.org/file/bot$token/$filePath";
                    file_put_contents(__DIR__ . '/telegram_webhook.log', "Image URL: $imageUrl\n", FILE_APPEND);
                } else {
                    file_put_contents(__DIR__ . '/telegram_webhook.log', "Failed to retrieve image file.\n", FILE_APPEND);
                }
            }

            $sqlStmt = "INSERT INTO `tbltelegram` (`lead_id`, `chat_id`, `message`, `msg_type`, `timestamp`, `staff_id`, `json_detail`, `file_path`) 
                VALUES ('$lead_id', '$chat_id', '$text', '2', NOW(), 0, '$input', '$imageUrl')";
            $res = mysqli_query($conn, $sqlStmt);

            if (!$res) {
                file_put_contents(__DIR__ . '/telegram_webhook.log', "DB Error (insert tbltelegram): " . mysqli_error($conn) . "\n", FILE_APPEND);
            } else {
                file_put_contents(__DIR__ . '/telegram_webhook.log', "Message logged to tbltelegram.\n", FILE_APPEND);
            }

        } else {
            $sqlStmt = "INSERT INTO `it_crm_leads` (`name`, `dateadded`, `description`, `client_id`, `email`, `source`, `status`, `telegram_bot_id`) 
                VALUES ('$name', NOW(), '$text', '$chat_id', '$username', 4, 2, '$botId')";
            if (mysqli_query($conn, $sqlStmt)) {
                file_put_contents(__DIR__ . '/telegram_webhook.log', "New lead inserted for $chat_id\n", FILE_APPEND);
                $sqlStmt = "INSERT INTO `it_crm_notifications` (`isread`, `isread_inline`, `date`, `description`, `fromuserid`, `fromclientid`, `from_fullname`, `touserid`) 
                    VALUES(0, 0, NOW(), 'New Lead via Telegram', 0, '$chat_id', '$name', 1)";
                if (mysqli_query($conn, $sqlStmt)) {
                    file_put_contents(__DIR__ . '/telegram_webhook.log', "Notification inserted.\n", FILE_APPEND);
                } else {
                    file_put_contents(__DIR__ . '/telegram_webhook.log', "DB Error (notification insert): " . mysqli_error($conn) . "\n", FILE_APPEND);
                }
            } else {
                file_put_contents(__DIR__ . '/telegram_webhook.log', "DB Error (lead insert): " . mysqli_error($conn) . "\n", FILE_APPEND);
            }
        }
    }
}

mysqli_close($conn);
file_put_contents(__DIR__ . '/telegram_webhook.log', "=== End Webhook ===\n\n", FILE_APPEND);
?>
