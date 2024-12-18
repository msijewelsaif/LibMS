<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Ensure this path is correct

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['send_otp'])) {
        $email = $_POST['email'];

        // Generate a random 6-digit OTP
        $otp = rand(100000, 999999);
        $_SESSION['generated_otp'] = $otp;

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
            $mail->addAddress($email); // Recipient's email address

            $mail->isHTML(true);
            $mail->Subject = 'Your OTP Code';
            $mail->Body    = 'Your OTP code is: <b>' . $otp . '</b>';

            $mail->send();
            //echo 'OTP has been sent to your email!';
           

        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }

    if (isset($_POST['register'])) {
        $name = $_POST['name'];
        $username = $_POST['username'];
        $student_id = $_POST['student_id'];
        $dept = $_POST['dept'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $password = $_POST['password']; // Fetching the password from POST request
        $otp = $_POST['otp'];

        // Validate OTP
        if ($otp != $_SESSION['generated_otp']) {
            echo 'Invalid OTP!';
            exit;
        }

        // Hash the password before storing it
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Database connection
        $conn = new mysqli('localhost', 'root', '', 'library_MS');

        if ($conn->connect_error) {
            die('Connection Failed : ' . $conn->connect_error);
        } else {
            $stmt = $conn->prepare("INSERT INTO students (name, username, student_id, dept, email, phone, password, otp, is_verified) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 1)");
            $stmt->bind_param("ssssssss", $name, $username, $student_id, $dept, $email, $phone, $hashed_password, $otp);
            $stmt->execute();
            echo 'Registration successful!';
            $stmt->close();
            $conn->close();
        }
    }
}
?>
