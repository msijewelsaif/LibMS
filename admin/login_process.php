<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'library_ms'); // Update with your DB credentials

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Fetch user data from the database
    $stmt = $conn->prepare("SELECT * FROM admin_users WHERE username=?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        
        // Compare the plain-text password directly
        if ($password === $row['password']) { // Check without hashing
            $_SESSION['username'] = $username;
            $_SESSION['admin_name'] = $row['admin_name']; // Add admin name to session
            header('Location: admin_page.php'); // Redirect to dashboard
            exit();
        } else {
            echo '<div class="alert alert-danger text-center">Invalid password</div>';
        }
    } else {
        echo '<div class="alert alert-danger text-center">No user found</div>';
    }
    
    $stmt->close();
    $conn->close();
}
?>
