

<?php
require_once("../config/db_connection.php");

/* ==========================
   GET CLIENT IP
========================== */
function getClientIP() {
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        return $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        return $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        return $_SERVER['REMOTE_ADDR'];
    }
}

/* ==========================
   GET COUNTRY FROM IP
========================== */
function getCountryFromIP($ip) {

    // Free IP API (works in local dev)
    $url = "http://ip-api.com/json/" . $ip;

    $response = @file_get_contents($url);

    if ($response) {
        $data = json_decode($response, true);
        return $data['country'] ?? "Unknown";
    }

    return "Unknown";
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $request = trim($_POST["request_text"]);

    if (!empty($request)) {

        $stmt = $conn->prepare("INSERT INTO project_requests (request_text) VALUES (?)");

        if(!$stmt){
            echo "error";
            exit;
        }

        $stmt->bind_param("s", $request);

        if ($stmt->execute()) {

            /* ==========================
               SEND WHATSAPP (SAFE MODE)
            ========================== */

            try {

                $phone = "250794241623";
                $apikey = "7881168";

                /* ==========================
                   COLLECT CLIENT INFO
                ========================== */

                $clientIP = getClientIP();
                $country = getCountryFromIP($clientIP);

                $browser = $_POST["browser_info"] ?? "Unknown";
                $device = $_POST["device_info"] ?? "Unknown";

                /* ==========================
                   PROFESSIONAL MESSAGE
                ========================== */

                $message  = "🚀 *NEW PROJECT CONSULTATION REQUEST*\n";
                $message .= "━━━━━━━━━━━━━━━━━━\n\n";

                $message .= "👤 *Client Message:*\n";
                $message .= $request . "\n\n";

                $message .= "🌐 *Client Information*\n";
                $message .= "━━━━━━━━━━━━━━━━━━\n";
                $message .= "📍 IP Address: $clientIP\n";
                $message .= "🌍 Country: $country\n";
                $message .= "💻 Device: $device\n";
                $message .= "🌎 Browser: $browser\n\n";

                $message .= "📅 *Date:* " . date("F j, Y") . "\n";
                $message .= "⏰ *Time:* " . date("h:i A") . "\n";

                $message .= "\n━━━━━━━━━━━━━━━━━━\n";
                $message .= "💼 Potiphar Portfolio System";

                $message = urlencode($message);

                $url = "https://api.callmebot.com/whatsapp.php?phone=$phone&text=$message&apikey=$apikey";

                // CURL request
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_TIMEOUT, 5);
                curl_exec($ch);
                curl_close($ch);

            } catch (Exception $e) {
                // Ignore WhatsApp failure
            }

            echo "success";

        } else {
            echo "error";
        }

        $stmt->close();

    } else {
        echo "empty";
    }
}

$conn->close();
?>

