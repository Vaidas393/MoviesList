<?php
error_reporting(0);
ini_set('display_errors', 0);

$logedUserId = 0;

session_start();
$logedUserId = $_SESSION['logedUserId'];

if (isset($_POST["logOut"])) {
  unset ($_SESSION["logedUserId"]);
  header("Location: index.php");
}
 ?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="style.min.css">
 <link href="https://fonts.googleapis.com/css2?family=Special+Elite&display=swap" rel="stylesheet">
        <title>Movies</title>
  </head>
  <body>
    <nav>
      <a href="index.php">All movies</a>
      <?php if ($logedUserId >0): ?>
      <a href="add.php">Add movie</a>
      <?php endif; ?>
      <?php if ($logedUserId >0): ?>
      <a href="myMovies.php">My movies</a>
      <?php endif; ?>
      <?php if ($logedUserId <= 0): ?>
      <a href="signIn.php">Sign up</a>
      <?php endif; ?>
      <?php if ($logedUserId <= 0): ?>
      <a href="login.php">Sign in</a>
      <?php endif; ?>
      <form class="" action="header.php" method="post">
      <?php if ($logedUserId >0): ?>
        <button type="submit" name="logOut">Log out</button>
        <?php endif; ?>
      </form>
    </nav>
