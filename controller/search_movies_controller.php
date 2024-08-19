<?php
include("auth.php");
require_once('../config.php');
require(MODEL_PATH . 'movies_model.php');

$search = $_POST['search'];
$moviesModel = new MoviesModel("localhost", "root", "", "3243"); 
$search_data = $moviesModel->search_movies($search);

function getColor($vote)
{
    if ($vote >= 8) {
        return 'green';
    } elseif ($vote >= 5) {
        return 'orange';
    } else {
        return 'red';
    }
}

?>