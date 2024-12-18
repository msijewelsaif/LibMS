<?php
include 'config.php';
session_start();

$admin_id = $_SESSION['username'];
if (!isset($admin_id)) {
    header('location:login.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Admin Panel</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="adminCSS/admin_style.css?v=<?php echo time(); ?>">       
</head>

<body>
    <?php include 'admin_header1.php'; ?>

    <section class="dashboard">
        <h1 class="title">Library Dashboard</h1>
        <div class="box-container">
            
            <!-- Total Offline Books -->
            <div class="box">
    <?php
    $total_quantity = 0; // Initialize total_quantity
    if ($conn) {
        // Query to calculate the total quantity of books
        $query = "SELECT SUM(quantity) AS total_quantity FROM `offline_books`";
        $result = mysqli_query($conn, $query);
    
        if ($result) {
            $row = mysqli_fetch_assoc($result);
            $total_quantity = $row['total_quantity'] ?? 0; // Handle null case if no data is present
        } else {
            error_log("Error: " . mysqli_error($conn));
            echo "<p>Unable to calculate total quantity</p>";
        }
    } else {
        echo "<p>Database connection failed</p>";
    }
    ?>
    <h3><?php echo $total_quantity > 0 ? $total_quantity : 0; ?></h3> <!-- Display the total quantity -->
    <p>Total Offline Books</p>
</div>



            <!-- Total Online Books -->
            <div class="box">
                <?php
                $total_online_books = 0;
                $select_online_books = mysqli_query($conn, "SELECT * FROM `online_books`") or die('Query failed');
                $total_online_books = mysqli_num_rows($select_online_books);
                ?>
                <h3><?php echo $total_online_books; ?></h3>
                <p>Total Online Books</p>
            </div>

            <!-- Total Borrowed Books -->
            <div class="box">
                <?php
                $total_borrowed_books = 0;
                $select_borrowed_books = mysqli_query($conn, "SELECT * FROM `borrowed_books` WHERE status = 'borrowed'") or die('Query failed');
                $total_borrowed_books = mysqli_num_rows($select_borrowed_books);
                ?>
                <h3><?php echo $total_borrowed_books; ?></h3>
                <p>Currently Borrowed</p>
            </div>

            

           
            
            
        </div>
    </section>

    <script src="adminJSS/admin_script.js"></script>
</body>
</html>
