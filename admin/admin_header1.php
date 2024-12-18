<?php
if (isset($message)) {
   foreach ($message as $message) {
      echo '
         <div class="message">
            <span>' . $message . '</span>
            <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
         </div>
      ';
   }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Admin Dashboard</title>

   <!-- Link to admin_header.css -->
   <link rel="stylesheet" href="adminCSS/admin_header.css">
   
   <!-- Link to FontAwesome for icons -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<body>

<header class="header">
   <div class="flex">
      <a href="admin_page.php" class="logo">Library<span>Admin</span></a>
      <nav class="navbar">
        
         <a href="admin_crud.php">Books</a>
         <a href="admin_borrowed.php">Borrowed Books</a>
         <a href="admin_contacts.php">Students Application</a>
         <a href="complain.php">Student Complains</a>
         <a href="admin_reviews.php">Book Reviews</a>
         <a href="admin_users.php">Accounts</a>
         
      </nav>

      <div class="icons">
         <div id="menu-btn" class="fas fa-bars"></div>
         <div id="user-btn" class="fas fa-user">  </div>
      </div>

      <div class="account-box">
         <p>Username: <span><?php echo $_SESSION['username']; ?></span></p>
         <p>Admin Name: <span><?php echo $_SESSION['admin_name']; ?></span></p>
         <a href="logout.php" class="delete-btn">Logout</a>
      </div>
   </div>
</header>

<!-- Add your other content here -->
<script src="adminJSS/admin_script.js"></script>
</body>
</html>
