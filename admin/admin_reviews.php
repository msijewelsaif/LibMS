<?php
// Start the session and include the config file for database connection
include '../config.php'; 
session_start();

// Check if the user is logged in and if the session variables are set
if (!isset($_SESSION['username']) || !isset($_SESSION['admin_name'])) {
    // Redirect to login page if not logged in
    header("Location: login.php"); 
    exit();
}

// Delete selected reviews functionality
if (isset($_POST['delete_selected_reviews'])) {
    if (isset($_POST['selected_reviews']) && !empty($_POST['selected_reviews'])) {
        $review_ids = implode(",", array_map('intval', $_POST['selected_reviews']));
        $delete_reviews_query = "DELETE FROM `book_reviews` WHERE id IN ($review_ids)";

        if (mysqli_query($conn, $delete_reviews_query)) {
            $message = "Selected reviews deleted successfully!";
        } else {
            $message = "Failed to delete selected reviews. Please try again.";
        }
    } else {
        $message = "No reviews selected for deletion.";
    }
}

// Retrieve all reviews
$reviews_query = "SELECT * FROM `book_reviews` ORDER BY review_date DESC";
$reviews_result = mysqli_query($conn, $reviews_query);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Manage Reviews</title>
    <link rel="stylesheet" href="custom_css/admin_reviews.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        h1 {
            text-align: center;
        }

        .container {
            display: flex;
            justify-content: center;
            padding: 20px;
        }

        .reviews-table {
            width: 80%;
            margin: 0 auto;
            border-collapse: collapse;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .reviews-table th, .reviews-table td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }

        .reviews-table th {
            background-color: #4CAF50;
            color: white;
        }

        .reviews-table td a {
            color: red;
            text-decoration: none;
        }

        .reviews-table td a:hover {
            text-decoration: underline;
        }

        .back-button {
            display: block;
            width: 200px;
            margin: 20px auto;
            padding: 10px;
            text-align: center;
            background-color: #f44336;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }

        .back-button:hover {
            background-color: #e53935;
        }

        .select-all {
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

<?php if (isset($message)) { echo "<p>$message</p>"; } ?>

<h1>Admin - Manage Reviews</h1>

<!-- Form to delete selected reviews -->
<form method="POST" action="admin_reviews.php">
    <!-- Display Reviews Table -->
    <div class="container">
        <table class="reviews-table">
            <thead>
                <tr>
                    <th><input type="checkbox" id="select_all"></th>
                    <th>Review ID</th>
                    <th>Book ID</th>
                    <th>Username</th>
                    <th>Review</th>
                    <th>Rating</th>
                    <th>Review Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Check if the query returned any results
                if (mysqli_num_rows($reviews_result) > 0) {
                    while ($review = mysqli_fetch_assoc($reviews_result)) {
                        // Ensure review id exists and is not empty
                        if (isset($review['id']) && !empty($review['id'])) {
                            echo "<tr>";
                            echo "<td><input type='checkbox' name='selected_reviews[]' value='" . htmlspecialchars($review['id']) . "'></td>";
                            echo "<td>" . htmlspecialchars($review['id']) . "</td>";
                            echo "<td>" . htmlspecialchars($review['book_id']) . "</td>";
                            echo "<td>" . htmlspecialchars($review['username']) . "</td>";
                            echo "<td>" . nl2br(htmlspecialchars($review['review'])) . "</td>";
                            echo "<td>" . htmlspecialchars($review['rating']) . "/5</td>";
                            echo "<td>" . htmlspecialchars($review['review_date']) . "</td>";
                            echo "<td>
                                    <a href='edit_review.php?id=" . urlencode($review['id']) . "'>Edit</a> | 
                                    <a href='copy_review.php?id=" . urlencode($review['id']) . "'>Copy</a> | 
                                    <a href='?delete_review_id=" . urlencode($review['id']) . "' onclick='return confirm(\"Are you sure you want to delete this review?\")'>Delete</a>
                                  </td>";
                            echo "</tr>";
                        } else {
                            echo "<tr><td colspan='8'>Error: Missing review id in database</td></tr>";
                        }
                    }
                } else {
                    echo "<tr><td colspan='8'>No reviews found.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Select All Checkbox -->
    <div class="select-all">
        <input type="checkbox" id="select_all_checkbox"> Check all
    </div>

    <!-- Delete Selected Reviews Button -->
    <button type="submit" name="delete_selected_reviews" class="back-button">Delete Selected Reviews</button>
</form>

<!-- Back Button to Admin Dashboard -->
<a href="admin_page.php" class="back-button">Back to Admin Dashboard</a>

<script>
    // JavaScript for select all functionality
    document.getElementById('select_all_checkbox').addEventListener('change', function() {
        var checkboxes = document.querySelectorAll('input[name="selected_reviews[]"]');
        checkboxes.forEach(function(checkbox) {
            checkbox.checked = this.checked;
        });
    });

    document.getElementById('select_all').addEventListener('change', function() {
        var checkboxes = document.querySelectorAll('input[name="selected_reviews[]"]');
        checkboxes.forEach(function(checkbox) {
            checkbox.checked = this.checked;
        });
    });
</script>

</body>
</html>
