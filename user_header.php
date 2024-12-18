<?php



$message = isset($_SESSION['message']) ? $_SESSION['message'] : [];
unset($_SESSION['message']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>User Dashboard</title>

   <!-- Link to user_header.css -->
   <link rel="stylesheet" href="custom_css/user_header.css">
   
   <!-- Link to FontAwesome for icons -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<body>

<?php
if (!empty($message)) {
   foreach ($message as $msg) {
      echo '
         <div class="message">
            <span>' . htmlspecialchars($msg) . '</span>
            <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
         </div>
      ';
   }
}
?>

<header class="header">
   <div class="flex">
      <a href="user_dashboard.php" class="logo">Library<span>User</span></a>
      <nav class="navbar">
         <a href="dashboard.php">Home</a>
         <a href="book_review.php">Books Review</a>
         
         <a href="borrow_books.php">Borrowed Books</a>
         <a href="complain.php">Complain Box</a>
         <a href="application.php">Application Box</a>
      </nav>

      <div class="icons">
         <div id="menu-btn" class="fas fa-bars"></div>
         <div id="user-btn" class="fas fa-user"></div>
      </div>

      <div class="account-box">
         <p>Username: <span><?php echo htmlspecialchars($_SESSION['username']); ?></span></p>
         <p>User Name: <span><?php echo htmlspecialchars($_SESSION['name']); ?></span></p>
         <a href="logout.php" class="delete-btn">Logout</a>
      </div>
   </div>
</header>

<div class="container mt-4">
   <h2>Welcome to your Dashboard!</h2>
   <!-- Add your user dashboard content here -->
   <p>Here you can manage your profile, view available books, and more!</p>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
