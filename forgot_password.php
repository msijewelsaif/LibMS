<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Ensure this path is correct

session_start();

$host = 'localhost';
$db = 'library_ms';
$user = 'root';
$pass = '';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die('Connection Failed: ' . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['send_otp'])) {
        $username = $_POST['username'];

        // Prevent SQL injection
        $username = $conn->real_escape_string($username);

        // Check if the username exists
        $stmt = $conn->prepare("SELECT email FROM students WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->bind_result($email);
        $stmt->fetch();
        $stmt->close();

        if ($email) {
            // Generate a random 6-digit OTP
            $otp = rand(100000, 999999);
            $_SESSION['generated_otp'] = $otp;
            $_SESSION['username'] = $username;

            $mail = new PHPMailer(true);

            try {
                $mail->isSMTP();
                $mail->Host       = 'smtp.gmail.com';
                $mail->SMTPAuth   = true;
                $mail->Username   = 'pre2002047@gmail.com'; // Your Gmail address
                $mail->Password   = 'qrjs xosi ealf jjpx';  // Your Gmail App Password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port       = 587;

                $mail->setFrom('pre2002047@gmail.com', 'Library Management System');
                $mail->addAddress($email);

                $mail->isHTML(true);
                $mail->Subject = 'Your OTP Code';
                $mail->Body    = 'Your OTP code is: <b>' . $otp . '</b>';

                $mail->send();
                echo 'OTP has been sent to your email!';
            } catch (Exception $e) {
                echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }
        } else {
            echo 'Username not found.';
        }
    } elseif (isset($_POST['reset_password'])) {
        $username = $_SESSION['username'];
        $otp = $_POST['otp'];
        $new_password = password_hash($_POST['new_password'], PASSWORD_BCRYPT);

        // Validate OTP
        if ($otp != $_SESSION['generated_otp']) {
            echo 'Invalid OTP!';
            exit;
        }

        // Update the password
        $stmt = $conn->prepare("UPDATE students SET password = ? WHERE username = ?");
        $stmt->bind_param("ss", $new_password, $username);
        $stmt->execute();

        echo 'Password has been reset successfully!';
        $stmt->close();
        $conn->close();
    }
}
?>
