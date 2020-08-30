<?php
error_reporting(0);
ini_set('display_errors', 0);


session_start();
$userMovies = $_POST["userMovies"];
$_SESSION['moviesByUser'] = $userMovies;




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


$sql = "SELECT movies.picture, movies.title, movies.genre, movies.year, movies.rating, users.username, users.id
        FROM movies LEFT JOIN users ON movies.user_id = users.id ORDER BY $selectedSort";

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

if(isset($_POST['userMovies'])){
  header("Location: moviesByUser.php");
}
?>

<header id="header">
  <?php include('header.php'); ?>
</header>


<section class="sorting">

<h4>Sort by:</h4>
<form action="index.php" method="POST">
  <select  name="orderBy[]"  onchange="this.form.submit()">
   <option value="title">Title</option>
   <option value="genre">Genre</option>
   <option value="year">Year</option>
   <option  value="rating DESC">Rating</option>
  </select>
</form>

<h4>Filter by ratings:</h4>
<form action="index.php" method="POST">
<select  name="filterBy[]"  onchange="this.form.submit()">
  <?php
    for ($i=0; $i <=10 ; $i+=0.5) {
      echo "<option value='$i'>$i</option>";
    }
   ?>
</select>
</form>
<a href="index.php">Clear filters</a>

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
    <form action="index.php" method="POST">
      <button class="userBtn" type="submit" name="userMovies" value="<?php echo htmlspecialchars($movie['username']); ?>"><?php echo htmlspecialchars($movie["username"]); ?></button>
  </form>
</div>
</div>
<?php } ?>

</section>

<footer>
  <?php include('footer.php'); ?>
</footer>
