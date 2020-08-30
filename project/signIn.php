<?php
error_reporting(0);
ini_set('display_errors', 0);
include('config/dbConnection.php');

$username ='';
$email ='';
$password ='';
$password2 ='';

$errors = ["username"=> "", "email" => "", "password" => "", "password2" => ""];

if (isset($_POST["submit"])) {
  $username = $_POST["username"];
  $email = $_POST["email"];
  $password = $_POST["password"];
  $password2 = $_POST["password2"];

  if(empty($_POST["username"]) || ctype_space($_POST["username"])){
    $errors["username"] = "Seriously, you forgot to fill a username? <br/>";
  } else {
    $username = $_POST["username"];
    if (!preg_match('/^[A-Za-z0-9?!*+ .,\-]+$/', $username)) {
      $errors["username"] = "Username, only 35 following characters allowed A-Z a-z 0-9 ? , . - ! * + " . "<br/>";
      $username = "labaiJauIlgasTestoUserneimasNuKurioNiekasNepasirinks";
    }
  }

  $sql= "SELECT * FROM users WHERE username = '$username' LIMIT 1 ";
  $result = mysqli_query($conn, $sql);
  $users = mysqli_fetch_all($result, MYSQLI_ASSOC);

  if (count($users) > 0) {
      $errors["username"] = "Username already exists" . "<br/>";
  }

  if(empty($_POST["email"]) || ctype_space($_POST["email"])){
    $errors["email"] = "Enter an email <br/>";
  } else {
    $email = $_POST["email"];
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $errors["email"] = "Invalid email" . "<br/>";
    }
  }

  $sql= "SELECT * FROM users WHERE email = '$email' LIMIT 1 ";
  $result = mysqli_query($conn, $sql);
  $emails = mysqli_fetch_all($result, MYSQLI_ASSOC);

  if (count($emails) > 0) {
      $errors["email"] = "Email already exists" . "<br/>";
  }

  if(empty($_POST["password"]) || ctype_space($_POST["password"])){
    $errors["password"] = "Seriously, you forgot to fill a password? <br/>";
  } else {
    $password = $_POST["password"];
    if (!preg_match('/^[A-Za-z0-9?!*+ .,\-]+$/', $password)) {
      $errors["password"] = "Password, only 35 following characters allowed A-Z a-z 0-9 ? , . - ! * + " . "<br/>";
    }
  }

  if(empty($_POST["password2"]) || ctype_space($_POST["password2"])){
    $errors["password2"] = "Seriously, you forgot to fill a password2? <br/>";
  } else {
    $password2 = $_POST["password2"];
    if (!preg_match('/^[A-Za-z0-9?!*+ .,\-]+$/', $password2)) {
      $errors["password2"] = "Password2, only 35 following characters allowed A-Z a-z 0-9 ? , . - ! * + " . "<br/>";
    }
  }

  if($password != $password2){
    $errors["password"] = "Password don't match " . "<br/>";
  }

  if (array_filter($errors)) {
    echo "";
    } else {
      $username = mysqli_real_escape_string($conn, $_POST["username"]);
      $email = mysqli_real_escape_string($conn, $_POST["email"]);
      $password = mysqli_real_escape_string($conn, $_POST["password"]);

      $sql= "INSERT INTO users(username, email, password) VALUES('$username', '$email', '$password')";

      if(mysqli_query($conn, $sql)){
        header("Location: login.php");
        } else{
        echo "ERROR: Could not able to execute $sql. " . mysqli_error($conn);
      }

  } //end of if, if there are no errors
} //end of mane if, if isset
?>

<header>
  <?php include('header.php'); ?>
</header>

<section class="signUp">

  <form class="" action="signIn.php" method="post">
    <label>Username:</label>
    <input type="text" name="username" maxlength="35" value="">
    <p class="errors"><?php  echo $errors["username"];?></p>
    <label>Email:</label>
    <input type="text" name="email" maxlength="35" value="">
    <p class="errors"><?php  echo $errors["email"];?></p>
    <label>Password:</label>
    <input type="password" name="password" minlength="5" maxlength="35" value="<?php echo $password; ?>">
    <p class="errors"><?php  echo $errors["password"];?></p>
    <label>Confirm password:</label>
    <input type="password" name="password2" minlength="5" maxlength="35" value="">
    <p class="errors"><?php  echo $errors["password2"];?></p>
    <button type="submit" name="submit">Submit</button>
  </form>

</section>

<?php include('footer.php') ?>
