<?php
include 'config.php';
session_start();

// Check if the user is logged in
if (!isset($_SESSION['student_id'])) {
    header('Location: login.php');
    exit;
}

// Fetch all available books (books that have quantity > 0)
$query = "SELECT * FROM `offline_books` WHERE `quantity` > 0";
$books_result = mysqli_query($conn, $query);

// Process form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['student_id']; // Assuming student ID is stored in session
    $email = $_POST['email'];
    $mobile = $_POST['mobile'];
    $book_id = $_POST['book_id'];

    // Insert borrow request into borrowed_books table
    $borrow_query = "INSERT INTO `borrowed_books` (user_id, email, mobile, book_id) 
                     VALUES ('$user_id', '$email', '$mobile', '$book_id')";
    
    if (mysqli_query($conn, $borrow_query)) {
        $borrow_success = true;
    } else {
        $borrow_error = true;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Borrow Book</title>
    <link rel="stylesheet" href="custom_css/borrowed.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f9f9f9;
        }
        .content-container {
            display: flex;
            gap: 20px;
        }
        .guidelines {
            flex: 1;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .borrow-form {
            flex: 1;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        button {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
        #backButton {
          
            display: block;
            margin-bottom: 20px;
        }
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
        }
        .modal-content {
            background: white;
            margin: 15% auto;
            padding: 20px;
            width: 50%;
            text-align: center;
            border-radius: 10px;
        }

        back-button {
        display: block; /* Ensures it takes up the full width */
       
       margin-bottom: 20px; /* Add some spacing below */
        padding: 10px 20px;
        border-radius: 5px;
        text-decoration: none;
        margin-bottom: 20px; /* Add some spacing below */
        text-align: center; /* Centers text within the button */
        width: fit-content; /* Adjusts width to fit content */
    }

   
    </style>
</head>
<body>
    <h1>Borrow a Book</h1>
    
    <div class="content-container">
        <!-- Guidelines Section -->
        <div class="guidelines">
            <h2>Guidelines for Borrowing Books</h2>
            <ul>
                <li><strong>Eligibility:</strong> Only registered students can borrow books.</li>
                <li><strong>Available Books:</strong> You can borrow books that are available in the library. Books with a quantity of 0 cannot be borrowed.</li>
                <li><strong>Borrowing Limit:</strong> You can borrow a maximum of 2 books at a time.</li>
                <li><strong>Borrowing Duration:</strong> Books must be returned within 10 days from the borrowing date.</li>
                <li><strong>Return Process:</strong> Once you have finished reading, please return the book in the same condition to avoid fines.</li>
                <li><strong>Fines:</strong> Late returns will incur a fine. Please ensure to return books on time.</li>
                <li><strong>Book Availability:</strong> If a book is unavailable, you can wait for it to be restocked or request a different book.</li>
            </ul>
        </div>
        
        <!-- Borrow Form -->
        <div class="borrow-form">
        <a href="dashboard.php" id="backButton" class="back-button"><button type="button">Back to Dashboard</button></a>
            <form action="borrow_books.php" method="POST">
                <label for="email">Email:</label>
                <input type="email" name="email" required><br><br>

                <label for="mobile">Mobile Number:</label>
                <input type="text" name="mobile" required><br><br>

                <label for="book_id">Select Book:</label>
                <select name="book_id" required>
                    <?php while ($row = mysqli_fetch_assoc($books_result)) { ?>
                        <option value="<?php echo $row['id']; ?>"><?php echo $row['name'] . ' by ' . $row['author']; ?></option>
                    <?php } ?>
                </select><br><br>

                <button type="submit">Request Borrow</button>
                
            </form>
        </div>
    </div>

    <!-- Modal -->
    <div id="modal" class="modal">
        <div class="modal-content">
            <?php if (isset($borrow_success) && $borrow_success) { ?>
                <h3>Success!</h3>
                <p>Your book borrow request has been submitted successfully!</p>
            <?php } elseif (isset($borrow_error) && $borrow_error) { ?>
                <h3>Error</h3>
                <p>There was an error processing your request. Please try again.</p>
            <?php } ?>
            <button id="closeModal">Close</button>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            <?php if (isset($borrow_success) || isset($borrow_error)) { ?>
                $('#modal').show(); // Show modal if success or error
            <?php } ?>

            // Close the modal
            $('#closeModal').click(function() {
                $('#modal').hide();
            });
        });
    </script>
</body>
</html>
