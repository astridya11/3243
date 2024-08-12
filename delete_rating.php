<?php
require('database.php');

if (isset($_GET['id'])) {
    $ratingID = $_GET['id'];

    // Delete the review
    $query = "DELETE FROM rating WHERE ratingID = ?";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, 'i', $ratingID);

    if (mysqli_stmt_execute($stmt)) {
        header('Location: movie.php'); // Redirect to the movie page
    } else {
        echo "Error: " . mysqli_error($con);
    }
}
?>
