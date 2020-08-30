<?php
error_reporting(0);
ini_set('display_errors', 0);

session_start();
$id = $_SESSION['updateId'];

include("config/dbConnection.php");

$picture = $title= $genre = $year = $rating = "";

$errors = ["picture"=> "", "title"=> "", "genre"=> "", "year"=> "", "rating"=> ""];

$sql = "SELECT * FROM movies WHERE movies.id = $id";
$result = mysqli_query($conn, $sql);
$movies = mysqli_fetch_all($result, MYSQLI_ASSOC);

if (isset($_POST["submitUpdate"])) {
  if(empty($_POST["picture"]) || ctype_space($_POST["picture"])){
    $errors["picture"] = "Add link to a picture <br/>";
  }else{
    $picture = $_POST["picture"];
  }

  if(empty($_POST["title"])|| ctype_space($_POST["title"])){
    $errors["title"] = "Seriously, you forgot a title? <br/>";
  }else{
    $title = $_POST["title"];
    if(!preg_match('/^[ ?,.0-9A-Za-z\-]+$/', $title)){
      $errors["title"] = "Title, only 35 following characters allowed A-Z a-z 0-9 ? , . -" . "<br/>";
    }
  }

  if(empty($_POST["genre"])|| ctype_space($_POST["genre"])){
    $errors["genre"] = "What's the genre of a movie? <br/>";
  }else{
    $genre = $_POST["genre"];
    if(!preg_match('/^[A-Za-z, ]+$/', $genre)){
      $errors["genre"] = "Gendre, only 35 following characters allowed A-Z a-z ," . "<br/>";
    }
  }

  if(empty($_POST["year"])){
    $errors["year"] = "Don't forget a year! <br/>";
  }else{
    $year = $_POST["year"];
    if(!preg_match('/^[0-9]+$/', $year)){
      $errors["year"] = "Choose year of the movie" . "<br/>";
    }
  }

  if(empty($_POST["rating"])|| ctype_space($_POST["rating"])){
    $errors["rating"] = "Rate it before you submit it :) <br/>";
  }else{
    $rating = $_POST["rating"];
    if(!preg_match('/^[,.0-9]+$/', $rating)){
      $errors["rating"] = "Rating, only 35 following characters allowed 0-9. , " . "<br/>";
    }
  }

    if(array_filter($errors)){

    } else {
      $picture = mysqli_real_escape_string($conn, $_POST["picture"]);
      $title = mysqli_real_escape_string($conn, $_POST["title"]);
      $genre = mysqli_real_escape_string($conn, $_POST["genre"]);
      $year = mysqli_real_escape_string($conn, $_POST["year"]);
      $rating = mysqli_real_escape_string($conn, $_POST["rating"]);

      $sql = "UPDATE movies SET picture = '$picture',  title = '$title', genre = '$genre', year = $year, rating = $rating WHERE movies.id = $id";

      if(mysqli_query($conn, $sql)){
        header("Location: index.php");
        } else{
        echo "ERROR: Could not able to execute $sql. " . mysqli_error($conn);
      }
    }

} //end of all post check
?>

<header>
  <?php include('header.php'); ?>
</header>

<section class="updateForm">
  <div class="moviesDiv">
  <?php foreach ($movies as $movie) { ?>
    <form class="addForm" action="update.php" method="post">
      <h4>Picture link:</h4> <br/>
      <input type="text" name="picture" value="<?php echo ($movie["picture"]); ?>">
      <p class="errors"><?php echo $errors["picture"]; ?><p>
      <br>
      <h4>Title:</h4> <br/>
      <input type="text" name="title" maxlength="35" value="<?php echo htmlspecialchars($movie["title"]); ?>">
      <p class="errors"><?php echo $errors["title"]; ?><p>
      <br>
      <h4>Genre:</h4> <br/>
      <input type="text" name="genre"maxlength="35" value="<?php echo htmlspecialchars($movie["genre"]); ?>">
      <p class="errors"><?php echo $errors["genre"]; ?><p>
      <br>
      <h4>Year:</h4> <br/>
      <select class="" name="year">
        <?php
          for ($i=2020; $i>=1900 ; $i--) {
            echo "<option value='$i'>$i</option>";
          }
         ?>
       </select>
       <p class="errors"><?php echo $errors["year"]; ?><p>
      <br>
      <h4>Your rating:</h4> <br/>
     <select class="" name="rating">
       <?php
         for ($i=0; $i <=10 ; $i+=0.5) {
           echo "<option value='$i'>$i</option>";
         }
        ?>
      </select>
      <br>
      <p class="errors"><?php echo $errors["rating"]; ?><p>
        <button type="submit" name="submitUpdate" value="update">Submit</button>
    </form>
  <?php } ?>
</div>

</section>

<footer>
  <?php include('footer.php'); ?>
</footer>
