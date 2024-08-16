<?php
include("auth.php");
require_once('../config.php');
require(MODEL_PATH . 'movies_model.php');

$movieID=$_REQUEST['movieID'];
$moviesModel = new MoviesModel("localhost", "root", "", "3243");
$result = $moviesModel->delete_movies($movieID);

header("Location: ../view/movies_dashboard.php");
exit();
?>