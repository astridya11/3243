<?php
include("auth.php");
require(MODEL_PATH.'database.php');

$currentUserID = $_SESSION['userID'];

$query = "SELECT movies.movieID, movies.title, movies.imageURL FROM watchedlist JOIN  movies ON watchedlist.movieID = movies.movieID
    WHERE watchedlist.userID = '$currentUserID'";
$result = mysqli_query($con, $query) or die(mysqli_error($con));
$rows = mysqli_num_rows($result);

if ($rows > 0) 
{
    $watchedlistData = mysqli_fetch_all($result, MYSQLI_ASSOC);
} 
else {
    $status = "It's empty here, record your movie journey!";
}
?>