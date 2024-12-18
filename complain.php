<?php
include 'config.php';

$status_message = ""; // To hold the modal content
$complaint_id_message = ""; // To hold the complaint ID message

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['submit_complaint'])) {
        // Handle complaint submission
        $username = $_POST['username'];
        $complaint_text = $_POST['complaint_text'];

        $sql = "INSERT INTO complaints (username, complaint_text, created_at, status) 
                VALUES ('$username', '$complaint_text', NOW(), 'pending')";
        if ($conn->query($sql) === TRUE) {
            $last_id = $conn->insert_id;
            $complaint_id_message = "Your complaint has been submitted successfully. Your Complaint ID is <b>$last_id</b>.";
        } else {
            $complaint_id_message = "Error: " . $conn->error;
        }
    } elseif (isset($_POST['check_status'])) {
        // Handle complaint status check
        $complaint_id = $_POST['complaint_id'];

        $sql = "SELECT * FROM complaints WHERE complaint_id = '$complaint_id'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $status_message = "<b>Complaint ID:</b> " . $row['complaint_id'] . "<br>"
                            . "<b>Complaint Text:</b> " . $row['complaint_text'] . "<br>"
                            . "<b>Status:</b> " . $row['status'] . "<br>";
            if ($row['resolved_at'] != NULL) {
                $status_message .= "<b>Resolved At:</b> " . $row['resolved_at'] . "<br>";
            }
        } else {
            $status_message = "No complaint found with the given ID.";
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Complaint Management</title>
    <link rel="stylesheet" href="custom_css/complain.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
            display: flex;
            flex-direction: row;
        }

        .sidebar {
            width: 25%;
            background-color: #007bff;
            color: #fff;
            padding: 20px;
            height: 100vh;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.2);
        }

        .sidebar h3 {
            margin-bottom: 20px;
        }

        .sidebar ul {
            list-style: none;
            padding: 0;
        }

        .sidebar ul li {
            margin-bottom: 15px;
        }

        .sidebar ul li::before {
            content: "✔";
            margin-right: 10px;
            color: #fff;
        }

        .container {
            width: 75%;
            padding: 20px;
        }

        h2 {
            color: #333;
        }

        input, textarea, button {
            width: 100%;
            margin: 10px 0;
            padding: 10px;
            font-size: 16px;
            border-radius: 5px;
            border: 1px solid #ddd;
        }

        button {
            background-color: #007bff;
            color: #fff;
            cursor: pointer;
            border: none;
        }

        button:hover {
            background-color: #0056b3;
        }

        .back-dashboard {
            margin: 20px 0;
            display: inline-block;
            text-decoration: none;
            color: #007bff;
            font-size: 18px;
        }

        .back-dashboard:hover {
            text-decoration: underline;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .modal-content {
            background-color: #fff;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 40%;
            border-radius: 8px;
            text-align: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
        }

        .modal-message {
            margin: 20px 0;
            font-size: 18px;
            line-height: 1.5;
        }

        .back-button {
            background-color: #007bff;
            color: #fff;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            font-size: 16px;
        }

        .back-button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

    <div class="sidebar">
        <h3>Guidelines</h3>
        <ul>
            <li>Enter your username accurately.</li>
            <li>Provide detailed and clear complaints.</li>
            <li>Remember to save your Complaint ID for status checking.</li>
            <li>Check the status periodically.</li>
        </ul>
    </div>

    <div class="container">
        <a href="dashboard.php" class="back-dashboard">← Back to Dashboard</a>

        <h2>Submit a Complaint</h2>
        <form method="post" action="">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>

            <label for="complaint_text">Complaint Text:</label>
            <textarea id="complaint_text" name="complaint_text" rows="4" required></textarea>

            <button type="submit" name="submit_complaint">Submit Complaint</button>
        </form>

        <h2>Check Complaint Status</h2>
        <form method="post" action="">
            <label for="complaint_id">Complaint ID:</label>
            <input type="number" id="complaint_id" name="complaint_id" required>

            <button type="submit" name="check_status">Check Status</button>
        </form>
    </div>

    <!-- Modal -->
    <div id="statusModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <div id="modalMessage" class="modal-message">
                <?php echo $status_message; ?>
                <?php echo $complaint_id_message; ?>
            </div>
            <a href="complain.php" class="back-button">Back to Complaint Page</a>
        </div>
    </div>

    <script>
        // Show the modal if there's a message
        function showModal() {
            const modal = document.getElementById('statusModal');
            modal.style.display = 'block';
        }

        // Close the modal
        function closeModal() {
            const modal = document.getElementById('statusModal');
            modal.style.display = 'none';
        }

        // Auto-show modal if there's content from PHP
        <?php if (!empty($status_message) || !empty($complaint_id_message)) { ?>
        document.addEventListener("DOMContentLoaded", () => {
            showModal();
        });
        <?php } ?>
    </script>
</body>
</html>
