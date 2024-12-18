<?php
include 'config.php';  // Include database connection file
session_start();

// Get the book ID from the URL (or assign a default ID for testing)
$book_id = isset($_GET['book_id']) ? $_GET['book_id'] : 1;
$username = $_SESSION['username'];  // Assuming user is logged in

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Handle form submission
    $review = mysqli_real_escape_string($conn, $_POST['review']);
    $rating = $_POST['rating'];

    // Insert the review into the database
    $insert_review_query = "INSERT INTO `book_reviews` (book_id, username, review, rating) 
                            VALUES ('$book_id', '$username', '$review', '$rating')";
    if (mysqli_query($conn, $insert_review_query)) {
        $message = "Review submitted successfully!";
        // Trigger modal by setting message
        $modal_message = "Your review has been successfully submitted!";
    } else {
        $message = "Failed to submit review. Please try again.";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Review</title>
    <link rel="stylesheet" href="custom_css/book_review.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        h1, h2 {
            text-align: center;
        }

        .container {
            display: flex;
            justify-content: center;
            padding: 20px;
        }

        .form-container {
            width: 60%;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .guidelines {
            width: 30%;
            padding: 20px;
            background-color: #fff;
            margin-right: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .review-form label {
            display: block;
            margin-bottom: 8px;
        }

        .review-form select, .review-form textarea, .review-form button {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        .review-form button {
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }

        .review-form button:hover {
            background-color: #45a049;
        }

        .back-button {
            background-color: #f44336;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .back-button:hover {
            background-color: #e53935;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .modal-content {
            background-color: #fff;
            padding: 20px;
            margin: 20% auto;
            width: 50%;
            border-radius: 8px;
            text-align: center;
        }

        .close {
            color: #aaa;
            font-size: 28px;
            font-weight: bold;
            position: absolute;
            top: 10px;
            right: 20px;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
</head>
<body>

<?php if (isset($message)) { echo "<p>$message</p>"; } ?>

<div class="container">
    <!-- Guidelines Section -->
    <div class="guidelines">
        <h3>Guidelines for Writing a Review</h3>
        <ul>
            <li>Be honest and objective in your review.</li>
            <li>Rate the book based on your overall experience.</li>
            <li>Explain why you liked or disliked the book.</li>
            <li>Keep it respectful and constructive.</li>
        </ul>
    </div>

    <!-- Review Form Section -->
    <div class="form-container">
        <h1>Review the Book</h1>
        <form class="review-form" action="" method="POST">
            <div>
                <label for="rating">Rating (1 to 5):</label>
                <select name="rating" id="rating" required>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                </select>
            </div>

            <div>
                <label for="review">Review:</label>
                <textarea name="review" id="review" rows="4" required></textarea>
            </div>

            <button type="submit">Submit Review</button>
        </form>

        <!-- Back Button -->
        <a href="javascript:history.back()" class="back-button">Back</a>
    </div>
</div>

<!-- Modal After Submission -->
<?php if (isset($modal_message)): ?>
    <div id="reviewModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2><?php echo $modal_message; ?></h2>
        </div>
    </div>
<?php endif; ?>

<!-- Display Reviews -->
<h2>User Reviews</h2>

<?php
// Retrieve and display all reviews for the current book
$reviews_query = "SELECT * FROM `book_reviews` WHERE book_id = '$book_id' ORDER BY review_date DESC";
$reviews_result = mysqli_query($conn, $reviews_query);

if (mysqli_num_rows($reviews_result) > 0) {
    while ($review = mysqli_fetch_assoc($reviews_result)) {
        echo "<div class='review'>";
        echo "<p><strong>Rating: " . $review['rating'] . "/5</strong></p>";
        echo "<p>" . nl2br($review['review']) . "</p>";
        echo "<p><small>Reviewed on: " . $review['review_date'] . "</small></p>";
        echo "</div>";
    }
} else {
    echo "<p>No reviews yet.</p>";
}
?>

<script>
    // Modal script
    var modal = document.getElementById("reviewModal");
    var span = document.getElementsByClassName("close")[0];

    if (modal) {
        modal.style.display = "block";

        // Close the modal
        span.onclick = function() {
            modal.style.display = "none";
        }

        // Close the modal if clicked outside
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    }
</script>

</body>
</html>
