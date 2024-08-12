<?php
require('database.php');
$movieID=$_REQUEST['movieID'];
$query = "DELETE FROM movies where movieID='".$movieID."'";
$result = mysqli_query($con,$query) or die ( mysqli_error($con));
header("Location: movies_dashboard.php");
exit();
?>