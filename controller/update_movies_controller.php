<?php
include("auth.php");

require_once('../config.php');
require(MODEL_PATH . 'movies_model.php');

$moviesModel = new MoviesModel("localhost", "root", "", "3243");

$status = "";
if (isset($_POST['new']) && $_POST['new'] == 1) {
    $movieID = $_REQUEST['movieID'];
    $title = $_REQUEST['title'];
    $dateReleased = $_REQUEST['dateReleased'];
    $duration = $_REQUEST['duration'];
    $genre = $_REQUEST['genre'];
    $language = $_REQUEST['language'];
    $country = $_REQUEST['country'];
    $director = $_REQUEST['director'];
    $cast = $_REQUEST['cast'];
    $synopsis = $_REQUEST['synopsis'];
    $imageURL = $_REQUEST["imageURL"];
    $videoURL_1 = $_REQUEST["videoURL_1"];
    $videoURL_2 = $_REQUEST["videoURL_2"];
    $videoURL_3 = $_REQUEST["videoURL_3"];
    $videoURL_4 = $_REQUEST["videoURL_4"];
    $videoURL_5 = $_REQUEST["videoURL_5"];
    $videoURL_6 = $_REQUEST["videoURL_6"];
    $videoURL_7 = $_REQUEST["videoURL_7"];
    $videoURL_8 = $_REQUEST["videoURL_8"];
    $videoURL_9 = $_REQUEST["videoURL_9"];
    $videoURL_10 = $_REQUEST["videoURL_10"];
    $submittedby = $_SESSION["user_id"] ?? 0;

    if ($moviesModel->update_movies($movieID, $title, $dateReleased, $duration, $genre, $language, $country, $director, $cast, $synopsis, $imageURL, $videoURL_1, $videoURL_2, $videoURL_3, $videoURL_4, $videoURL_5, $videoURL_6, $videoURL_7, $videoURL_8, $videoURL_9, $videoURL_10)) {
        echo "<script>
        alert('Movie successfully updated!');
        window.location.href = '../view/movies_details.php?movieID=" . $movieID . "';
    </script>";
    } else {
        $status = "Error updating record.";
    }
} else {
    $movieID = $_REQUEST['movieID'];
    $row = $moviesModel->read_movies_details($movieID);
    include(VIEW_PATH . 'update_movies.php');
}
