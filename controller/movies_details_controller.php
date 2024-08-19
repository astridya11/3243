<?php
require(MODEL_PATH . 'movies_model.php');

$movieID = $_REQUEST['movieID'];

$userID = $_SESSION['userID'] ?? null;
$userRole = $_SESSION['userRole'] ?? null;

$moviesModel = new MoviesModel("localhost", "root", "", "3243");
$row = $moviesModel->read_movies_details($movieID);

$genresArray = explode(", ", $row['genre']);

$videoURLs = [
    $row['videoURL_1'],
    $row['videoURL_2'],
    $row['videoURL_3'],
    $row['videoURL_4'],
    $row['videoURL_5'],
    $row['videoURL_6'],
    $row['videoURL_7'],
    $row['videoURL_8'],
    $row['videoURL_9'],
    $row['videoURL_10'],

];

function getEmbedUrl($url)
{
    // Parse the URL to get the query parameters
    $queryString = parse_url($url, PHP_URL_QUERY);
    parse_str($queryString, $queryParams);
    // Extract the video ID
    $videoID = $queryParams['v'];
    // Construct the embedded URL
    return "https://www.youtube.com/embed/$videoID";
}

if (isset($_POST['watchedButton'])) {
    if (!isset($_SESSION['userID'])) 
    {
        // Redirect to login page or show an error
        header("Location: ../");
        exit();
    }

    // check whether already in watchedlist
    $check_result = $moviesModel->check_watched_list($movieID, $userID);

    if ($check_result) {
        echo '<script>
        alert("Movie is already in your watched list!");
        window.location.href = "movies_details.php?movieID=' . $movieID . '";
        </script>';
    } else {
        if ($moviesModel->insert_watched_list($movieID, $userID)) {
            echo '<script>alert("Movie successfully added to your watched list!");
             window.location.href = "movies_details.php?movieID=' . $movieID . '";
            </script>';
        } else {
            echo '<script>
            alert("Watched list updates unsuccessful! Please try again later.");
            window.location.href = "movies_details.php?movieID=' . $movieID . '";
            </script>';
        }
    }
}


if (isset($_POST['wishlistButton'])) {
    if (!isset($_SESSION['userID'])) 
    {
        // Redirect to login page or show an error
        header("Location: ../");
        exit();
    }

    // check whether already in wishlist
    $check_result = $moviesModel->check_wish_list($movieID, $userID);

    if ($check_result) {
        echo '<script>
        alert("Movie is already in your wishlist!");
        window.location.href = "movies_details.php?movieID=' . $movieID . '";
        </script>';
    } else {
        if ($moviesModel->insert_wish_list($movieID, $userID)) {
            echo '<script>
            alert("Movie successfully added to your wishlist!");
            window.location.href = "movies_details.php?movieID=' . $movieID . '";
            </script>';
        } else {
            echo'<script>
            alert("Wish list updates unsuccessful! Please try again later.");
            window.location.href = "movies_details.php?movieID=' . $movieID . '";
            </script>';
        }
    }
}


if (isset($_POST['updateButton'])) {
    header("Location: ../controller/update_movies_controller.php?movieID=" . urlencode($movieID));
}


if (isset($_POST['deleteButton'])) {
    echo '<script>
            confirm("Are you sure you want to delete this movie?");
            window.location.href = "../controller/delete_movies_controller.php?movieID=' . $movieID . '";
            </script>';
}
