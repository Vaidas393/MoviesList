<?php
error_reporting(0);
ini_set('display_errors', 0);

session_start();
$userMovies = $_SESSION['moviesByUser'];

include("config/dbConnection.php");

$selectedSort = "title";
    if(!empty($_POST['orderBy'])) {
      foreach($_POST['orderBy'] as $selected){
        $selectedSort =  $selected;
      }
    }

$sql = "SELECT movies.picture, movies.title, movies.genre, movies.year, movies.rating, users.username
        FROM movies LEFT JOIN users ON movies.user_id = users.id WHERE users.username ='".$userMovies."' ORDER BY title" ;

if ($selectedSort != "") {
    $sql = "SELECT movies.picture, movies.title, movies.genre, movies.year, movies.rating, users.username, users.id
            FROM movies LEFT JOIN users ON movies.user_id = users.id WHERE users.username ='".$userMovies."' ORDER BY $selectedSort";
        }

$result = mysqli_query($conn, $sql);
$movies = mysqli_fetch_all($result, MYSQLI_ASSOC);
mysqli_close($conn);
?>

<header>
  <?php include('header.php'); ?>
</header>

<section class="sorting">

<h4>Sort by:</h4>

<form action="moviesByUser.php" method="POST">
<select  name="orderBy[]"  onchange="this.form.submit()">
 <option value="title">Title</option>
 <option value="genre">Genre</option>
 <option value="year">Year</option>
 <option  value="rating DESC">Rating</option>
</select>
</form>
</section>

<section class="username">
  <h2>Movies by user: <?php echo htmlspecialchars("$userMovies"); ?></h2>
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
  </div>
</div>
<?php } ?>

</section>

<footer>
  <?php include('footer.php'); ?>
</footer>
