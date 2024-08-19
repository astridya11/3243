<?php
include("auth.php");
require_once("../config.php");
require(MODEL_PATH . 'movies_model.php');

$moviesModel = new MoviesModel("localhost", "root", "", "3243");
$submittedBy = $_SESSION['userID'] ?? 0;
$status = "";

if (isset($_POST['manual'])) {
    $movieID = 'movie' . date('YmdHis');
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

    //check whether title already exists
    $check_result = $moviesModel->check_existing_movies($title, $dateReleased);

    if ($check_result) {
        echo '<script>
        alert("Movie already exists. Please enter a different movie.");
        window.location.href = "../view/insert_movies.php";
        </script>';
    } else {
        if ($moviesModel->insert_movies($movieID, $title, $dateReleased, $duration, $genre, $language, $country, $director, $cast, $synopsis, $imageURL, $videoURL_1, $videoURL_2, $videoURL_3, $videoURL_4, $videoURL_5, $videoURL_6, $videoURL_7, $videoURL_8, $videoURL_9, $videoURL_10, $submittedBy)) {
            echo '<script>alert("Movie record successfully created!");
                window.location.href = "../view/movies_dashboard.php";
               </script>';
        } else {
            echo '<script>
               alert("Movie record updates unsuccessful! Please try again later.");
               window.location.href = "../view/insert_movies.php";
               </script>';
        }
    }
} else if (isset($_POST['callAPI']) && $_POST['callAPI'] == 1) {

    sleep(2);

    $data = $_POST['data'];
    function object_to_array($data)
    {
        return (array) $data;
    }
    $movie = object_to_array($data);


    if (is_array($movie)) {
        $movieID = 'movie' . date('YmdHis');
        $title = $movie['title'];
        $dateReleased = $movie['dateReleased'];
        $duration = $movie['duration'];
        $genre = $movie['genre'];
        $language = $movie['language'];
        $country = $movie['country'];
        $director = $movie['director'];
        $cast = $movie['cast'];
        $synopsis = $movie['synopsis'];
        $imageURL = $movie['imageURL'];
        $videoURL_1 = $movie["videoURL_1"];
        $videoURL_2 = $movie["videoURL_2"];
        $videoURL_3 = $movie["videoURL_3"];
        $videoURL_4 = $movie["videoURL_4"];
        $videoURL_5 = $movie["videoURL_5"];
        $videoURL_6 = $movie["videoURL_6"];
        $videoURL_7 = $movie["videoURL_7"];
        $videoURL_8 = $movie["videoURL_8"];
        $videoURL_9 = $movie["videoURL_9"];
        $videoURL_10 = $movie["videoURL_10"];

        // Check if movie with the same title already exists
        //check whether title already exists
        $check_result = $moviesModel->check_existing_movies($title, $dateReleased);

        if ($check_result) {
            $status = "<span style='color: red;'>Movie already exists. Please enter a different movie.";
        } else {
            $moviesModel->insert_movies($movieID, $title, $dateReleased, $duration, $genre, $language, $country, $director, $cast, $synopsis, $imageURL, $videoURL_1, $videoURL_2, $videoURL_3, $videoURL_4, $videoURL_5, $videoURL_6, $videoURL_7, $videoURL_8, $videoURL_9, $videoURL_10, $submittedBy);
        }

    }
}

?>
