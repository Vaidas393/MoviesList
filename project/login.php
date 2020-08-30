<?php
error_reporting(0);
ini_set('display_errors', 0);

session_start();

include('config/dbConnection.php');

$username ="";
$password ="";
$loginId = "";

$errors = ["username"=> "", "password" => ""];


if (isset($_POST["submit"])) {
  $username = $_POST["username"];
  $password = $_POST["password"];

  if(empty($_POST["username"]) || ctype_space($_POST["username"])){
    $errors["username"] = "Seriously, you forgot a username? <br/>";
  } else {
    $username = $_POST["username"];
    if (!preg_match('/^[A-Za-z0-9?!*+ .,\-]+$/', $username)) {
      $errors["username"] = "Username, only 35 following characters allowed A-Z a-z 0-9 ? , . - ! * + " . "<br/>";
    }
  }

  if(empty($_POST["email"]) || ctype_space($_POST["email"])){
    $errors["email"] = "Enter an email <br/>";
  } else {
    $email = $_POST["email"];
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $errors["email"] = "Invalid email" . "<br/>";
    }
  }

  if (array_filter($errors)) {
    $username = mysqli_real_escape_string($conn, $_POST["username"]);
    $password = mysqli_real_escape_string($conn, $_POST["password"]);
    $user['id'] ="0";

    $sql= "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    $result = mysqli_query($conn, $sql);
    $users = mysqli_fetch_all($result, MYSQLI_ASSOC);

    foreach ($users as $user) {
    }

    if ($user["id"] > 0) {
      $loginId = $user["id"];
      $_SESSION['logedUserId'] = $loginId;
      header("Location: index.php");
    } else {
      $errors["email"] = "Incorect username or password <br/>";
    }

  } //end of if, if there are no errors
} //end of mane if, if isset
?>


<header>
  <?php include('header.php'); ?>
</header>

<section class="logIn">

<form class="" action="login.php" method="post">
  <label>Username:</label>
  <input type="text" name="username" maxlength="35" value="<?php echo $username;?>">
  <label>Password:</label>
  <input type="password" name="password" maxlength="35" value="<?php echo $password; ?>">
  <p class="errors"><?php  echo $errors["password"];?></p>
  <p class="errors"><?php  echo $errors["email"];?></p>
  <button type="submit" name="submit">Submit</button>
</form>

</section>

<?php include('footer.php') ?>
