<?php
error_reporting(0);
ini_set('display_errors', 0);

session_start();
$logedUserId = $_SESSION['logedUserId'];

include("config/dbConnection.php");
$selectedSort = "title";
    if(!empty($_POST['orderBy'])) {
      foreach($_POST['orderBy'] as $selected){
        $selectedSort =  $selected;
      }
    }

$selectedfilter = "";
    if(!empty($_POST['filterBy'])) {
      foreach($_POST['filterBy'] as $selected){
        $selectedfilter =  $selected;
      }
    }

$sql = "SELECT movies.id, movies.picture, movies.title, movies.genre, movies.year, movies.rating, users.username
        FROM movies LEFT JOIN users ON movies.user_id = users.id WHERE users.id = $logedUserId ORDER BY $selectedSort";

if ($selectedfilter != "") {
  $sql = "SELECT movies.picture, movies.title, movies.genre, movies.year, movies.rating, users.username, users.id
          FROM movies LEFT JOIN users ON movies.user_id = users.id WHERE movies.rating = $selectedfilter ORDER BY $selectedSort";
}

$result = mysqli_query($conn, $sql);

$movies = mysqli_fetch_all($result, MYSQLI_ASSOC);

if ($movies ==[]) {
  $validationError =  "We don't have movies with such rating";
} else {
  $validationError = "";
}

foreach ($movies as $movie) {
  $_SESSION['updateId'] = $movie["id"];
}

if (isset($_POST["delete"])) {
  $movieToDelete = mysqli_real_escape_string($conn, $_POST["deleteId"]);

  $sql = "DELETE FROM movies WHERE id = $movieToDelete ";

  if(mysqli_query($conn, $sql)){
    header("Location: index.php");
    } else {
    echo mysqli_error($conn);
  }

}

if (isset($_POST["update"])) {
  $movieToUpdate = $_POST['updateId'];
  $_SESSION['updateId'] = $movieToUpdate;
  header("Location: update.php");
}
?>

<header>
  <?php include('header.php'); ?>
</header>

section class="sorting">

<h4>Sort by:</h4>
<form action="myMovies.php" method="POST">
  <select  name="orderBy[]"  onchange="this.form.submit()">
   <option value="title">Title</option>
   <option value="genre">Genre</option>
   <option value="year">Year</option>
   <option  value="rating DESC">Rating</option>
  </select>
</form>

<h4>Filter by ratings:</h4>
<form action="myMovies.php" method="POST">
<select  name="filterBy[]"  onchange="this.form.submit()">
  <?php
    for ($i=0; $i <=10 ; $i+=0.5) {
      echo "<option value='$i'>$i</option>";
    }
   ?>
</select>
</form>
<a href="myMovies.php">Clear filters</a>

</section>

<section class="filterErrors">
    <?php echo htmlspecialchars($validationError); ?>
</section>


<section class="moviesWrapper">

<?php foreach ($movies as $movie) { ?>
<div class="allMovies">
  <div class="imgDiv">
    <img src="<?php echo htmlspecialchars($movie["picture"]); ?>" alt="image link unavailable">

  </div>
  <div class="descriptionDiv">
    <h6><?php echo htmlspecialchars($movie["title"]); ?></h6>
    <h6><?php echo htmlspecialchars($movie["genre"]); ?></h6>
    <h6><?php echo htmlspecialchars($movie["year"]); ?></h6>
    <h6><?php echo htmlspecialchars($movie["rating"]); ?></h6>
    <form class="" action="myMovies.php" method="post">
      <input type="hidden" name="updateId" value="<?php echo htmlspecialchars($movie["id"]); ?>">
      <button type="submit" name="update" value="update">Update</button>

    </form>
    <form class="" action="myMovies.php" method="post">
      <input type="hidden" name="deleteId" value="<?php echo htmlspecialchars($movie["id"]); ?>">
      <button type="submmit" name="delete" value="delete">Delete</button>
    </form>
</div>
</div>
<?php } ?>

</section>

<footer>
  <?php include('footer.php'); ?>
</footer>
