<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};

if(isset($_POST['submit'])){
   if (empty($_POST['captcha'])) {
      $message[] = "Please enter the CAPTCHA code!";
   } elseif ($_POST['captcha'] !== $_SESSION['captcha_code']) {
      $message[] = "The CAPTCHA code is incorrect!";
   } else {
      $email = $_POST['email'];
      $email = filter_var($email, FILTER_SANITIZE_STRING);
      $pass = sha1($_POST['pass']);
      $pass = filter_var($pass, FILTER_SANITIZE_STRING);

      $select_user = $conn->prepare("SELECT * FROM `users` WHERE email = ?");
      $select_user->execute([$email]);
      $row = $select_user->fetch(PDO::FETCH_ASSOC);

      if(!$row){
         $message[] = 'Email does not exist!';
      } else {
         if(!password_verify($pass, $row['pass'])){
            $message[] = 'Password is Incorrect!';
         } else {
            $_SESSION['user_id'] = $row['id'];
            header('location: home.php');
         }
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
   <title>ANBU Login</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<!-- header section starts  -->
<?php include 'components/user_header.php'; ?>
<!-- header section ends -->

<section class="form-container">

   <form action="" method="post">
      <h3>login now</h3>
      <input type="email" name="email" required placeholder="Enter Your Email" class="box" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="password" name="pass" required placeholder="Enter Your Password" class="box" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
      <img src="captcha.php" alt="CAPTCHA Image" /><br />
      <input type="text" name="captcha" required placeholder="Enter CAPTCHA" class="box" maxlength="50">
      <input type="submit" value="login now" name="submit" class="btn">
      <p>Don't Have An Account? <a href="register.php">Register Now</a></p>
   </form>

</section>











<?php include 'components/footer.php'; ?>






<!-- custom js file link  -->
<script src="js/script.js"></script>

</body>
</html>