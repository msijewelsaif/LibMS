<?php
include 'config.php';
session_start();

$admin_id = $_SESSION['username'];
if (!isset($admin_id)) {
    header('location:login.php');
}

// Handle book approval when the 'Approve' link is clicked
if (isset($_GET['approve_id'])) {
    $borrow_id = $_GET['approve_id'];
    
    // Fetch the borrow request details
    $query = "SELECT * FROM `borrowed_books` WHERE `id` = '$borrow_id'";
    $result = mysqli_query($conn, $query);
    $borrow = mysqli_fetch_assoc($result);
    
    if ($borrow) {
        // Calculate return date (10 days from now)
        $return_date = date('Y-m-d', strtotime('+10 days'));

        // Update the status to 'approved' and set the return date
        $update_query = "UPDATE `borrowed_books` SET `status` = 'approved', `return_date` = '$return_date' WHERE `id` = '$borrow_id'";
        
        if (mysqli_query($conn, $update_query)) {
            // Fetch the corresponding book details
            $book_id = $borrow['book_id'];
            $book_query = "SELECT quantity FROM `offline_books` WHERE id = '$book_id'";
            $book_result = mysqli_query($conn, $book_query);
            $book = mysqli_fetch_assoc($book_result);
            
            if ($book) {
                // Decrease the book quantity by 1 when approved
                $new_quantity = $book['quantity'] - 1;
                $update_quantity_query = "UPDATE `offline_books` SET `quantity` = '$new_quantity' WHERE id = '$book_id'";
                if (mysqli_query($conn, $update_quantity_query)) {
                    echo "Book borrow request approved. The book must be returned by $return_date. Book quantity has been updated.";
                } else {
                    echo "Error updating book quantity: " . mysqli_error($conn);
                }
            }
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    }
}

// Handle book return when the 'Return' link is clicked
if (isset($_GET['return_id'])) {
    $borrow_id = $_GET['return_id'];
    
    // Fetch the borrow request details
    $query = "SELECT * FROM `borrowed_books` WHERE `id` = '$borrow_id'";
    $result = mysqli_query($conn, $query);
    $borrow = mysqli_fetch_assoc($result);
    
    if ($borrow) {
        // Update the status to 'returned'
        $update_query = "UPDATE `borrowed_books` SET `status` = 'returned' WHERE `id` = '$borrow_id'";
        
        if (mysqli_query($conn, $update_query)) {
            // Fetch the corresponding book details
            $book_id = $borrow['book_id'];
            $book_query = "SELECT quantity FROM `offline_books` WHERE id = '$book_id'";
            $book_result = mysqli_query($conn, $book_query);
            $book = mysqli_fetch_assoc($book_result);
            
            if ($book) {
                // Increase the book quantity by 1 when returned
                $new_quantity = $book['quantity'] + 1;
                $update_quantity_query = "UPDATE `offline_books` SET `quantity` = '$new_quantity' WHERE id = '$book_id'";
                if (mysqli_query($conn, $update_quantity_query)) {
                    echo "Book return processed successfully. Book quantity has been updated.";
                } else {
                    echo "Error updating book quantity: " . mysqli_error($conn);
                }
            }
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    }
}

// Fetch all pending borrow requests
$query = "SELECT * FROM `borrowed_books` WHERE `status` = 'pending'";
$result = mysqli_query($conn, $query);

// Fetch books that need to be returned
$return_query = "
    SELECT bb.id AS borrow_id, bb.user_id, bb.email, bb.mobile, bb.book_id, bb.borrowed_date, bb.return_date, bb.status, 
           ob.name AS book_name, ob.author 
    FROM `borrowed_books` bb 
    INNER JOIN `offline_books` ob ON bb.book_id = ob.id 
    WHERE bb.status = 'approved' OR bb.status = 'returned'
";
$return_result = mysqli_query($conn, $return_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="adminCSS/admin_borrowed.css">
    <title>Admin - Borrow Requests</title>
</head>
<body>
<button onclick="goBack()" style="margin: 20px; padding: 10px 20px; font-size: 16px;">Back</button>
    <h1>Pending Borrow Requests</h1>

    <!-- Pending Borrow Requests Table -->
    <table border="1">
        <thead>
            <tr>
                <th>User ID</th>
                <th>Email</th>
                <th>Mobile</th>
                <th>Book Name</th>
                <th>Author</th>
                <th>Request Date</th>
                <th>Approve</th>
            </tr>
        </thead>
        <tbody>
            <?php
            while ($row = mysqli_fetch_assoc($result)) {
                // Fetch book details for each borrow request
                $book_id = $row['book_id'];
                $book_query = "SELECT name, author FROM `offline_books` WHERE id = '$book_id'";
                $book_result = mysqli_query($conn, $book_query);
                $book = mysqli_fetch_assoc($book_result);
            ?>
            <tr>
                <td><?php echo $row['user_id']; ?></td>
                <td><?php echo $row['email']; ?></td>
                <td><?php echo $row['mobile']; ?></td>
                <td><?php echo $book['name']; ?></td>
                <td><?php echo $book['author']; ?></td>
                <td><?php echo $row['borrowed_date']; ?></td>
                <td>
                    <a href="?approve_id=<?php echo $row['id']; ?>">Approve</a>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>

    <h1>Books to be Returned</h1>

    <!-- Borrowed Books Table -->
    <table border="1">
        <thead>
            <tr>
                <th>User ID</th>
                <th>Email</th>
                <th>Mobile</th>
                <th>Book Name</th>
                <th>Author</th>
                <th>Borrowed Date</th>
                <th>Return Date</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            while ($row = mysqli_fetch_assoc($return_result)) {
                $is_late = (strtotime($row['return_date']) < time() && $row['status'] == 'approved') ? 'Late' : 'On Time';
            ?>
            <tr>
                <td><?php echo $row['user_id']; ?></td>
                <td><?php echo $row['email']; ?></td>
                <td><?php echo $row['mobile']; ?></td>
                <td><?php echo $row['book_name']; ?></td>
                <td><?php echo $row['author']; ?></td>
                <td><?php echo $row['borrowed_date']; ?></td>
                <td><?php echo $row['return_date']; ?></td>
                <td style="color: <?php echo $is_late == 'Late' ? 'red' : 'green'; ?>;">
                    <?php echo $is_late; ?>
                </td>
                <td>
                    <?php if ($row['status'] == 'approved') { ?>
                        <a href="?return_id=<?php echo $row['borrow_id']; ?>">Mark as Returned</a>
                    <?php } else { ?>
                        Returned
                    <?php } ?>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
    <script>
        function goBack() {
            window.history.back();
        }
    </script>
</body>
</html>
