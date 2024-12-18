<?php
   include 'config.php';

   session_start();

   $admin_id = $_SESSION['username'];
   if(!isset($admin_id)){
      header('location:login.php');
   }

   if(isset($_GET['delete'])){
      $delete_id = $_GET['delete'];
      mysqli_query($conn, "DELETE FROM `users` WHERE id = '$delete_id'") or die('query failed');
      header('location:admin_users.php');
   }
?>


<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="UTF-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Accounts</title>
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">   <!-- font awesome cdn link  -->
      < <link rel="stylesheet" href="adminCSS/admin_style.css?v=<?php echo time(); ?>">     
   </head>

   <body>
      <?php include 'admin_header1.php'; ?>

      <section class="users">
         <h1 class="title"> Accounts </h1>
         <div class="box-container">
            <?php
               $select_users = mysqli_query($conn, "SELECT * FROM `admin_users`") or die('query failed');
               while($fetch_users = mysqli_fetch_assoc($select_users)){
            ?>
            <div class="box">
               <p> Id: <span><?php echo $fetch_users['id']; ?></span> </p>
               
               <p> Username: <span><?php echo $fetch_users['username']; ?></span> </p>
               <p> Name: <span><?php echo $fetch_users['admin_name']; ?></span> </p>
               <a href="admin_users.php?delete=<?php echo $fetch_users['id']; ?>" onclick="return confirm('Delete this account?');" class="delete-btn">delete account</a>
            </div>
            <?php
               };
            ?>
         </div>
      </section>

      <script src="adminJSS/admin_script.js"></script>
   </body>
</html>