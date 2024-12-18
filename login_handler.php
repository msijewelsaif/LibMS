<?php
session_start();

$host = 'localhost'; 
$db   = 'library_ms'; 
$user = 'root'; 
$pass = ''; 

$conn = new mysqli($host, $user, $pass, $db);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Get form inputs
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Prevent SQL injection
    $username = $conn->real_escape_string($username);
    $password = $conn->real_escape_string($password);

    // Query to check if user exists
    $sql = "SELECT * FROM students WHERE username = '$username' LIMIT 1";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Check if the user is verified
        if ($user['is_verified'] == 1) {

            // Use password_verify() to check the password
            if (password_verify($password, $user['password'])) {
                
                // Success: Set session variables and redirect to dashboard
                $_SESSION['username'] = $user['username'];
                $_SESSION['name'] = $user['name'];
                $_SESSION['student_id'] = $user['student_id'];
                $_SESSION['dept'] = $user['dept'];

                // Redirect to the dashboard
                header("Location: dashboard.php");
                exit();
            } else {
                // Incorrect password
                $_SESSION['error'] = "Incorrect password!";
            }
        } else {
            // User not verified
            $_SESSION['error'] = "Your account is not verified. Please verify your email first.";
        }
    } else {
        // No user found with this username
        $_SESSION['error'] = "No user found with this username.";
    }

    // Redirect back to login page with error
    header("Location: login.html");
    exit();
}
?>
