<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
};

if(isset($_POST['submit'])){

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $pass = sha1($_POST['pass']);
   $pass = filter_var($pass, FILTER_SANITIZE_STRING);
   $cpass = sha1($_POST['cpass']);
   $cpass = filter_var($cpass, FILTER_SANITIZE_STRING);

   $select_admin = $conn->prepare("SELECT * FROM `admin` WHERE name = ?");
   $select_admin->execute([$name]);
   
   if($select_admin->rowCount() > 0){
      $message[] = 'Username Already Exists!';
   }else{
      if($pass != $cpass){
         $message[] = 'Confirm Passowrd Not Matched!';
      }else{
         $insert_admin = $conn->prepare("INSERT INTO `admin`(name, password) VALUES(?,?)");
         $insert_admin->execute([$name, $cpass]);
         $message[] = 'New Admin Registered!';
      }
   }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>ANBU Register</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

<?php include '../components/admin_header.php' ?>
<section class="form-container">

   <form action="" method="POST">
      <h3>Register New</h3>
      <input type="Text" name="Name" maxlength="20" required placeholder="Enter Your Username" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="Password" name="Pass" maxlength="20" required placeholder="Enter Your Password" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="Password" name="Cpass" maxlength="20" required placeholder="Confirm Your Password" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="Submit" value="Register Now" name="Submit" class="btn">
   </form>

</section>
<script src="../js/admin_script.js"></script>

</body>
</html>