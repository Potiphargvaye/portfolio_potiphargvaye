<?php
  /**
  * Requires the "PHP Email Form" library
  * The "PHP Email Form" library is available only in the pro version of the template
  * The library should be uploaded to: vendor/php-email-form/php-email-form.php
  * For more info and help: https://bootstrapmade.com/php-email-form/
  */

  // Replace contact@example.com with your real receiving email address
  $receiving_email_address = 'contact@example.com';

  if( file_exists($php_email_form = '../assets/vendor/php-email-form/php-email-form.php' )) {
    include( $php_email_form );
  } else {
    die( 'Unable to load the "PHP Email Form" Library!');
  }


  $contact->ajax = true;
  
  $contact->to = $receiving_email_address;
  $contact->from_name = $_POST['email'];
  $contact->from_email = $_POST['email'];
  $contact->subject ="New Subscription: " . $_POST['email'];

  // Uncomment below code if you want to use SMTP to send emails. You need to enter your correct SMTP credentials
  /*
  $contact->smtp = array(
    'host' => 'example.com',
    'username' => 'example',
    'password' => 'pass',
    'port' => '587'
  );
  */

  $contact->add_message( $_POST['email'], 'Email');

  echo $contact->send();
?>












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







<form id="projectRequestForm" action="forms/project-request.php" method="POST" class="cta-form">

  <input type="hidden" name="client_ip" id="client_ip">
  <input type="hidden" name="browser_info" id="browser_info">
  <input type="hidden" name="device_info" id="device_info">

  <div class="input-group mb-3">
    <textarea name="request_text"
              class="form-control"
              placeholder="Enter your project request details..."
              rows="3"
              required></textarea>

    <button id="submitConsultation" class="btn btn-primary" type="submit">
      <span class="btn-text">Request Consultation</span>
      <span class="spinner" style="display:none;"></span>
    </button>
  </div>

</form>



3. Add This JS (Auto Capture Browser + Device)

Put this in your main JS file:

// Capture client info automatically
document.addEventListener("DOMContentLoaded", function () {

  if (document.getElementById("browser_info")) {
    document.getElementById("browser_info").value = navigator.userAgent;
  }

  if (document.getElementById("device_info")) {
    document.getElementById("device_info").value =
      navigator.platform + " | " + navigator.language;
  }

});

🚀 NEW PROJECT CONSULTATION REQUEST
━━━━━━━━━━━━━━━━━━

👤 Client Message:
Hello I need a website...

🌐 Client Information
━━━━━━━━━━━━━━━━━━
📍 IP Address: 102.xxx.xxx.xxx
🌍 Country: Rwanda
💻 Device: Win32 | en-US
🌎 Browser: Mozilla/5.0 Chrome...

📅 Date: February 21, 2026
⏰ Time: 07:35 PM


Next Level Training (Highly Recommended)

Your chatbot will become extremely powerful if we next add:

✅ lead qualification responses
✅ portfolio project training
✅ sales conversion replies
✅ client objection handling
✅ AI personality behavior rules
✅ conversation flows

If you want, I can build those for you next.

Just say: