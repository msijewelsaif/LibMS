
<?php
// Start the session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username']) || !isset($_SESSION['admin_name'])) {
    // Redirect to login page if not logged in
    header("Location: login.php"); // Change this to your login page
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="adminCSS/admin_crud.css?v=<?php echo time(); ?>">
</head>
<body>
<section>
   <a href="admin_page.php"> <button class="btn back-btn">Back</button></a>
    

<?php
include 'config.php'; // Ensure this path is correct



// Ensure the database connection is established
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Handle deleting an offline book
if (isset($_GET['delete_offline'])) {
    $delete_id = mysqli_real_escape_string($conn, $_GET['delete_offline']);
    $delete_query = "DELETE FROM `offline_books` WHERE id='$delete_id'";
    mysqli_query($conn, $delete_query);
    header('Location: admin_crud.php'); // Redirect to avoid form resubmission
    exit;
}

// Handle deleting an online book
if (isset($_GET['delete_online'])) {
    $delete_id = mysqli_real_escape_string($conn, $_GET['delete_online']);
    $delete_query = "DELETE FROM `online_books` WHERE id='$delete_id'";
    mysqli_query($conn, $delete_query);
    header('Location: admin_crud.php'); // Redirect to avoid form resubmission
    exit;
}

// Handle adding an offline book
if (isset($_POST['add_offline_book'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $author = mysqli_real_escape_string($conn, $_POST['author']);
    $quantity = mysqli_real_escape_string($conn, $_POST['quantity']);
    $image = $_FILES['image']['name'];
    $image_tmp = $_FILES['image']['tmp_name'];
    
    // Move the uploaded image to the target directory
    if (move_uploaded_file($image_tmp, "upload_images/" . $image)) {
        $insert_query = "INSERT INTO `offline_books`(`name`, `author`, `quantity`, `image`) VALUES ('$name', '$author', '$quantity', '$image')";
        mysqli_query($conn, $insert_query) or die('query failed');
        header('Location: admin_crud.php'); // Redirect to avoid form resubmission
        exit;
    } else {
        echo "Failed to upload image.";
    }
}

// Handle adding an online book
if (isset($_POST['add_online_book'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $author = mysqli_real_escape_string($conn, $_POST['author']);
    $pdf = $_FILES['pdf']['name'];
    $pdf_tmp = $_FILES['pdf']['tmp_name'];
    
    // Move the uploaded PDF to the target directory
    if (move_uploaded_file($pdf_tmp, "uploaded_pdf/" . $pdf)) {
        $insert_query = "INSERT INTO `online_books`(`name`, `author`, `pdf`) VALUES ('$name', '$author', '$pdf')";
        mysqli_query($conn, $insert_query) or die('query failed');
        header('Location: admin_crud.php'); // Redirect to avoid form resubmission
        exit;
    } else {
        echo "Failed to upload PDF.";
    }
}

// Handle editing an offline book
if (isset($_GET['edit_offline'])) {
    $edit_id = mysqli_real_escape_string($conn, $_GET['edit_offline']);
    $fetch_edit_query = "SELECT * FROM `offline_books` WHERE id='$edit_id'";
    $edit_book_result = mysqli_query($conn, $fetch_edit_query);
    $edit_book = mysqli_fetch_assoc($edit_book_result);
}

if (isset($_POST['update_offline_book'])) {
    $edit_id = $_POST['edit_id'];
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $author = mysqli_real_escape_string($conn, $_POST['author']);
    $quantity = mysqli_real_escape_string($conn, $_POST['quantity']);
    $image = $_FILES['image']['name'];
    $image_tmp = $_FILES['image']['tmp_name'];

    // Update image only if a new one is uploaded
    if (!empty($image)) {
        move_uploaded_file($image_tmp, "upload_images/" . $image);
        $update_query = "UPDATE `offline_books` SET `name` = '$name', `author` = '$author', `quantity` = '$quantity', `image` = '$image' WHERE `id` = '$edit_id'";
    } else {
        // Keep the old image if none is uploaded
        $update_query = "UPDATE `offline_books` SET `name` = '$name', `author` = '$author', `quantity` = '$quantity' WHERE `id` = '$edit_id'";
    }
    mysqli_query($conn, $update_query) or die('query failed');
    header('Location: admin_crud.php'); // Redirect to avoid form resubmission
    exit;
}

// Handle editing an online book
if (isset($_GET['edit_online'])) {
    $edit_id = mysqli_real_escape_string($conn, $_GET['edit_online']);
    $fetch_edit_query = "SELECT * FROM `online_books` WHERE id='$edit_id'";
    $edit_book_result = mysqli_query($conn, $fetch_edit_query);
    $edit_book_online = mysqli_fetch_assoc($edit_book_result);
}

if (isset($_POST['edit_online_book'])) {
    $id = mysqli_real_escape_string($conn, $_POST['book_id']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $author = mysqli_real_escape_string($conn, $_POST['author']);
    
    // Update the online book details
    $update_query = "UPDATE `online_books` SET `name`='$name', `author`='$author' WHERE id='$id'";
    mysqli_query($conn, $update_query);
    header('Location: admin_crud.php'); // Redirect to avoid form resubmission
    exit;
}

// Search functionality for offline and online books
$search_query = '';
if (isset($_POST['search'])) {
    $search_query = mysqli_real_escape_string($conn, $_POST['search_query']);
}

// Query for offline books
$offline_books_query = "SELECT * FROM `offline_books` WHERE `name` LIKE '%$search_query%' OR `author` LIKE '%$search_query%'";
$select_offline_books = mysqli_query($conn, $offline_books_query) or die('query failed');

// Query for online books
$online_books_query = "SELECT * FROM `online_books` WHERE `name` LIKE '%$search_query%' OR `author` LIKE '%$search_query%'";
$select_online_books = mysqli_query($conn, $online_books_query) or die('query failed');
?>

<!-- Grid Layout for Offline and Online Books -->
<section class="crud-container">
    <!-- Add Offline Book (Physical Book) -->
    <div class="crud-form">
        <h1 class="title">Add Offline Book (Physical)</h1>
        <form action="" method="post" enctype="multipart/form-data">
            <h3>Add Offline Book</h3>
            <input type="text" name="name" class="box" placeholder="Enter book name" required value="<?php echo isset($edit_book) ? htmlspecialchars($edit_book['name']) : ''; ?>">
            <input type="text" name="author" class="box" placeholder="Enter author name" required value="<?php echo isset($edit_book) ? htmlspecialchars($edit_book['author']) : ''; ?>">
            <input type="number" min="0" name="quantity" class="box" placeholder="Enter quantity" required value="<?php echo isset($edit_book) ? htmlspecialchars($edit_book['quantity']) : ''; ?>">
            <input type="file" name="image" accept="image/jpg, image/jpeg, image/png" class="box">
            <input type="hidden" name="edit_id" value="<?php echo isset($edit_book) ? $edit_book['id'] : ''; ?>">
            <input type="submit" value="Update Book" name="update_offline_book" class="btn" style="<?php echo isset($edit_book) ? '' : 'display:none;'; ?>">
            <input type="submit" value="Add Book" name="add_offline_book" class="btn" style="<?php echo isset($edit_book) ? 'display:none;' : ''; ?>">
        </form>
    </div>

    <!-- Show Offline Books -->
    <div class="crud-table">
        <h1 class="title">Offline Books (Physical)</h1>
        <form method="post" class="search-form">
            <input type="text" name="search_query" placeholder="Search by book name or author" class="search-box">
            <input type="submit" name="search" value="Search" class="btn">
        </form>
        <table>
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Author</th>
                    <th>Quantity</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($select_offline_books)) : ?>
                <tr>
                    <td><img src="upload_images/<?php echo $row['image']; ?>" alt="Book Image" width="50"></td>
                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                    <td><?php echo htmlspecialchars($row['author']); ?></td>
                    <td><?php echo htmlspecialchars($row['quantity']); ?></td>
                    <td>
                        <a href="admin_crud.php?edit_offline=<?php echo $row['id']; ?>" class="edit-btn">Edit</a>
                        <a href="admin_crud.php?delete_offline=<?php echo $row['id']; ?>" class="delete-btn">Delete</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <!-- Add Online Book (PDF) -->
    <div class="crud-form">
        <h1 class="title">Add Online Book (PDF)</h1>
        <form action="" method="post" enctype="multipart/form-data">
            <h3>Add Online Book</h3>
            <input type="text" name="name" class="box" placeholder="Enter book name" required value="<?php echo isset($edit_book_online) ? htmlspecialchars($edit_book_online['name']) : ''; ?>">
            <input type="text" name="author" class="box" placeholder="Enter author name" required value="<?php echo isset($edit_book_online) ? htmlspecialchars($edit_book_online['author']) : ''; ?>">
            <input type="file" name="pdf" accept="application/pdf" class="box" required>
            <input type="hidden" name="book_id" value="<?php echo isset($edit_book_online) ? $edit_book_online['id'] : ''; ?>">
            <input type="submit" value="Update Book" name="edit_online_book" class="btn" style="<?php echo isset($edit_book_online) ? '' : 'display:none;'; ?>">
            <input type="submit" value="Add Book" name="add_online_book" class="btn" style="<?php echo isset($edit_book_online) ? 'display:none;' : ''; ?>">
        </form>
    </div>

    <!-- Show Online Books -->
    <div class="crud-table">
        <h1 class="title">Online Books (PDF)</h1>
        <form method="post" class="search-form">
            <input type="text" name="search_query" placeholder="Search by book name or author" class="search-box">
            <input type="submit" name="search" value="Search" class="btn">
        </form>
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Author</th>
                    <th>PDF File</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($select_online_books)) : ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                    <td><?php echo htmlspecialchars($row['author']); ?></td>
                    <td><a href="uploaded_pdf/<?php echo $row['pdf']; ?>" target="_blank">View PDF</a></td>
                    <td>
                        <a href="admin_crud.php?edit_online=<?php echo $row['id']; ?>" class="edit-btn">Edit</a>
                        <a href="admin_crud.php?delete_online=<?php echo $row['id']; ?>" class="delete-btn">Delete</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</section>
<script src="adminJSS/admin_script.js"></script>
</body>
</html>
