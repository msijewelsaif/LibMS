
<?php
include 'config.php';

$status_message = ""; // Holds modal content for success or error messages

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['submit_request'])) {
        $user_id = $_POST['user_id'];
        $name = $_POST['name'];
        $number = $_POST['number'];
        $email = $_POST['email'];
        $message = $_POST['message'];

        $sql = "INSERT INTO message (user_id, name, number, email, message, created_at) 
                VALUES ('$user_id', '$name', '$number', '$email', '$message', NOW())";

        if ($conn->query($sql) === TRUE) {
            $status_message = "Your request for an extended deadline has been submitted successfully.";
        } else {
            $status_message = "Error: " . $conn->error;
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Request Extended Deadline</title>
    <link rel="stylesheet" href="custom_css/extended_deadline.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .main-container {
            display: flex;
            width: 80%;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .guideline {
            width: 40%;
            background-color: #007bff;
            color: #fff;
            padding: 20px;
        }

        .guideline h3 {
            color: #fff;
            margin-bottom: 15px;
        }

        .guideline ul {
            list-style-type: none;
            padding: 0;
        }

        .guideline ul li {
            margin-bottom: 10px;
            font-size: 14px;
            line-height: 1.5;
        }

        .guideline ul li:before {
            content: "✔️";
            margin-right: 10px;
            color: #fff;
        }

        .form-container {
            width: 60%;
            padding: 20px;
            text-align: center;
        }

        h2 {
            color: #333;
        }

        label {
            display: block;
            text-align: left;
            font-size: 14px;
            margin-bottom: 5px;
            color: #555;
        }

        input, textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
        }

        button {
            background-color: #007bff;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: 0.3s ease;
        }

        button:hover {
            background-color: #0056b3;
        }

        /* Modal styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            overflow: auto;
        }

        .modal-content {
            background-color: #fff;
            margin: 15% auto;
            padding: 20px;
            border-radius: 8px;
            width: 40%;
            text-align: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .close {
            font-size: 20px;
            color: #aaa;
            float: right;
            cursor: pointer;
        }

        .close:hover {
            color: black;
        }

        .modal-message {
            font-size: 16px;
            line-height: 1.5;
            margin: 20px 0;
        }

        .back-button {
        display: block; /* Ensures it takes up the full width */
        background-color: #007bff;
        color: white;
        padding: 10px 20px;
        border-radius: 5px;
        text-decoration: none;
        margin-bottom: 20px; /* Add some spacing below */
        text-align: center; /* Centers text within the button */
        width: fit-content; /* Adjusts width to fit content */
    }

    .back-button:hover {
        background-color: #0056b3;
    }
    </style>
</head>
<body>

    <div class="main-container">
        <!-- Guideline Section -->
        <div class="guideline">
            <h3>How to Submit Your Application</h3>
            <ul>
                <li>Fill out your User ID as shown in your library account.</li>
                <li>Enter your full name for identification.</li>
                <li>Provide your contact number in case the admin needs to reach you.</li>
                <li>Use a valid email address to receive updates about your request.</li>
                <li>In the message section, clearly explain why you need an extension.</li>
                <li>Double-check your information before clicking "Submit Request."</li>
            </ul>
        </div>

        <!-- Form Section -->
        <div class="form-container">
        <a href="dashboard.php" class="back-button">Back to Dashboard</a>
            <h2>Request Extended Deadline</h2>
            <form method="post" action="">
                <label for="user_id">User ID:</label>
                <input type="text" id="user_id" name="user_id" required>

                <label for="name">Name:</label>
                <input type="text" id="name" name="name" required>

                <label for="number">Contact Number:</label>
                <input type="text" id="number" name="number" required>

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>

                <label for="message">Reason for Extension:</label>
                <textarea id="message" name="message" rows="4" required></textarea>

                <button type="submit" name="submit_request">Submit Request</button>
            </form>
    

        </div>
    </div>

    <!-- Modal -->
    <div id="statusModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <div class="modal-message">
                <?php echo $status_message; ?>
            </div>
            <a href="dashboard.php" class="back-button">Back to Dashboard</a>
        </div>
    </div>

    <script>
        // Show modal if there's a PHP message
        function showModal() {
            const modal = document.getElementById('statusModal');
            modal.style.display = 'block';
        }

        // Close modal
        function closeModal() {
            const modal = document.getElementById('statusModal');
            modal.style.display = 'none';
        }

        // Automatically show modal if PHP message exists
        <?php if (!empty($status_message)) { ?>
        document.addEventListener("DOMContentLoaded", () => {
            showModal();
        });
        <?php } ?>
    </script>

</body>
</html>
