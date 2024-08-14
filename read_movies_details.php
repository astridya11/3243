<?php
include("auth.php");
require('database.php');

// retrive movie info
$movieID = $_REQUEST['movieID'];
$query = "SELECT * FROM movies where movieID='" . $movieID . "'";
$result = mysqli_query($con, $query) or die(mysqli_error($con));
$row = mysqli_fetch_assoc($result);
$userRole = $_SESSION['userRole'];

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

// Function to transform URL
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
    $watchedListID = 'watchedList' . date('YmdHis');
    $userID = $_SESSION["userID"];

    // check whether already in watchedlist
    $check_query = "SELECT * FROM watchedlist WHERE movieID='$movieID' AND userID='$userID'";
    $check_result = mysqli_query($con, $check_query);

    if (mysqli_num_rows($check_result) > 0) {
        echo "<script>alert('Movie is already in your watchedlist!');</script>";
    } else {

        $ins_query = "INSERT INTO watchedlist 
        (watchedListID, movieID, userID) values
        ('$watchedListID', '$movieID', '$userID')";


        if (mysqli_query($con, $ins_query)) {
            echo "<script>alert('Movie successfully added to your watched list!');</script>";
        } else {
            die(mysqli_error($con));
        }
    }
}


if (isset($_POST['wishlistButton'])) {
    $wishlistID = 'wishlist' . date('YmdHis');
    $userID = $_SESSION["userID"];

    // check whether already in wishlist
    $check_query = "SELECT * FROM wishlist WHERE movieID='$movieID' AND userID='$userID'";
    $check_result = mysqli_query($con, $check_query);

    if (mysqli_num_rows($check_result) > 0) {
        echo '<script>
        alert("Movie is already in your wishlist!");
        window.location.href = "movies_details.php?movieID=' . $movieID . '";
        </script>';
    } else {


        $ins_query = "INSERT INTO wishlist 
        (wishlistID, movieID, userID) values
        ('$wishlistID', '$movieID', '$userID')";

        if (mysqli_query($con, $ins_query)) {
            echo `<script>
            alert("Movie successfully added to your wishlist!");
            window.location.href = "movies_details.php?movieID=' . $movieID . '";
            </script>`;
        } else {
            die(mysqli_error($con));
        }
    }
}


if (isset($_POST['updateButton'])) {
    header("Location: update_movies.php?movieID=" . urlencode($movieID));
}


if (isset($_POST['deleteButton'])) {
    echo '<script>
            confirm("Are you sure you want to delete this movie?");
            window.location.href = "delete_movies.php?movieID=' . $movieID . '";
            </script>';
}

?>
