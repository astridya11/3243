<?php
require(MODEL_PATH . 'movies_model.php');

$moviesModel = new MoviesModel("localhost", "root", "", "3243"); 
$data = $moviesModel->read_movies();

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