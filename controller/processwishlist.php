<?php
include("auth.php");
require(MODEL_PATH.'database.php');

$currentUserID = $_SESSION['userID'];

$query = "SELECT movies.movieID, movies.title, movies.imageURL FROM wishlist JOIN  movies ON wishlist.movieID = movies.movieID
    WHERE wishlist.userID = '$currentUserID'";
$result = mysqli_query($con, $query) or die(mysqli_error($con));
$rows = mysqli_num_rows($result);

if ($rows > 0) {
    $wishlistData = mysqli_fetch_all($result, MYSQLI_ASSOC);
} else {
    $status = "It's empty here, add some favourite movies!";
}
?>