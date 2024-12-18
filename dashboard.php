<?php
include 'config.php';
session_start();

$user_id = $_SESSION['username']; 
if (!isset($user_id)) {
    header('location:login.html');
}
// Search functionality for offline books
$offline_search_query = '';
if (isset($_POST['search_offline'])) {
    $search_term = mysqli_real_escape_string($conn, $_POST['search_term_offline']);
    $offline_search_query = "WHERE `name` LIKE '%$search_term%'";
}

// Search functionality for online books
$online_search_query = '';
if (isset($_POST['search_online'])) {
    $search_term = mysqli_real_escape_string($conn, $_POST['search_term_online']);
    $online_search_query = "WHERE `name` LIKE '%$search_term%'";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="custom_css/dashboard.css">
</head>

<body>
<?php include 'user_header.php'; ?>

<section class="dashboard">
    <h1 class="title">User Dashboard</h1>

    <!-- Offline Books Section -->
    <section class="books-section">
        <h2>Available Offline Books</h2>
        
        <!-- Offline Book Search -->
        <form method="POST" class="search-form">
            <input type="text" name="search_term_offline" placeholder="Search offline books" class="search-input">
            <button type="submit" name="search_offline" class="search-btn">Search</button>
        </form>

        <div class="books-container">
            <?php
            $select_offline_books = mysqli_query($conn, "SELECT * FROM `offline_books` $offline_search_query") or die('Query failed');
            if (mysqli_num_rows($select_offline_books) > 0) {
                while ($book = mysqli_fetch_assoc($select_offline_books)) {
            ?>
                    <div class="book">
                        <img class="icon" src="admin/upload_images/<?php echo $book['image']; ?>" alt="<?php echo $book['name']; ?>">
                        <div class="book-details">
                            <h3><?php echo htmlspecialchars($book['name']); ?></h3>
                            <p>Author: <?php echo htmlspecialchars($book['author']); ?></p>
                            <p>Quantity: <?php echo htmlspecialchars($book['quantity']); ?></p>
                            <p><strong>Book ID:</strong> <?php echo htmlspecialchars($book['id']); ?></p>
                        </div>
                    </div>
            <?php
                }
            } else {
                echo "<p>No offline books available.</p>";
            }
            ?>
        </div>
    </section>

    <!-- Online Books Section -->
    <section class="books-section">
        <h2>Available Online Books</h2>

        <!-- Online Book Search -->
        <form method="POST" class="search-form">
            <input type="text" name="search_term_online" placeholder="Search online books" class="search-input">
            <button type="submit" name="search_online" class="search-btn">Search</button>
        </form>

        <div class="books-container">
    <?php
    $select_online_books = mysqli_query($conn, "SELECT * FROM `online_books` $online_search_query") or die('Query failed');
    if (mysqli_num_rows($select_online_books) > 0) {
        while ($book = mysqli_fetch_assoc($select_online_books)) {
    ?>
            <div class="book">
                <div class="book-details">
                    <h3><?php echo htmlspecialchars($book['name']); ?></h3>
                    <p>Author: <?php echo htmlspecialchars($book['author']); ?></p>
                    <!-- Ensure the download button is clickable and correctly linked -->
                    <a href="admin/uploaded_pdf/<?php echo htmlspecialchars($book['pdf']); ?>" download class="btn">
    <?php 
        if (file_exists("admin/uploaded_pdf/" . $book['pdf'])) {
            echo "Download PDF";
        } else {
            echo "File not available";
        }
    ?>
</a>

                    <p><strong>Book ID:</strong> <?php echo htmlspecialchars($book['id']); ?></p>
                </div>
            </div>
    <?php
        }
    } else {
        echo "<p>No online books available.</p>";
    }
    ?>
</div>

    </section>
</section>

<script src="userJSS/user_script.js"></script>
</body>
</html>