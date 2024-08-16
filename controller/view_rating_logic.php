<?php
require_once('../config.php');
require(MODEL_PATH . 'database.php');

// Fetch the movieID
$movieID = isset($_GET['movieID']) ? trim($_GET['movieID']) : null;

if (!$movieID) {
    echo "Error: Movie not found! (movieID not provided)";
    exit;
}

// Check if user has already rated the movie
$userID = $_SESSION['userID'] ?? null;
$ratingCount = 0;

if ($userID) {
    $checkRatingQuery = "SELECT COUNT(*) as count FROM rating WHERE movieID = ? AND userID = ?";
    $checkRatingStmt = mysqli_prepare($con, $checkRatingQuery);
    mysqli_stmt_bind_param($checkRatingStmt, 'ss', $movieID, $userID);
    mysqli_stmt_execute($checkRatingStmt);
    $checkRatingResult = mysqli_stmt_get_result($checkRatingStmt);
    $ratingCount = mysqli_fetch_assoc($checkRatingResult)['count'];
}

// Fetch ratings and reviews from database
$ratingQuery = "SELECT ratingStar FROM rating WHERE movieID = ?";
$ratingStmt = mysqli_prepare($con, $ratingQuery);
mysqli_stmt_bind_param($ratingStmt, 's', $movieID);
mysqli_stmt_execute($ratingStmt);
$ratingResult = mysqli_stmt_get_result($ratingStmt);

// Fetch rating with lastModified field and user details, prioritizing logged-in user's comment
$reviewQuery = "SELECT r.ratingID, r.ratingTime, r.ratingDate, r.ratingDescription, r.ratingStar, r.lastModified, r.userID as reviewUserID, u.userName, u.userProfilePic
                FROM rating r
                JOIN users u ON r.userID = u.userID
                WHERE r.movieID = ?
                ORDER BY CASE WHEN r.userID = ? THEN 0 ELSE 1 END, r.ratingDate ASC, r.ratingTime ASC"; // Prioritize the logged-in user's comment
$stmt = mysqli_prepare($con, $reviewQuery);
mysqli_stmt_bind_param($stmt, 'ss', $movieID, $userID);
mysqli_stmt_execute($stmt);
$reviewResult = mysqli_stmt_get_result($stmt);


if ($ratingResult) {
    $totalRatings = 0;
    $ratingCounts = [1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0];
    $totalReviews = mysqli_num_rows($ratingResult);

    while ($row = mysqli_fetch_assoc($ratingResult)) {
        $ratingStar = $row['ratingStar'];
        $totalRatings += $ratingStar;
        if (isset($ratingCounts[$ratingStar])) {
            $ratingCounts[$ratingStar]++;
        }
    }

    $averageRating = $totalReviews > 0 ? round($totalRatings / $totalReviews, 1) : 0;
} else {
    echo "Error: " . mysqli_error($con);
    exit;
}

// Function to calculate time ago
function timeAgo($datetime)
{
    $now = new DateTime();
    $ago = new DateTime($datetime);
    $diff = $now->getTimestamp() - $ago->getTimestamp();

    if ($diff < 60) {
        return "just now";
    } elseif ($diff < 3600) { // Less than an hour
        $minutes = round($diff / 60);
        return "$minutes minute" . ($minutes > 1 ? 's' : '') . " ago";
    } elseif ($diff < 86400) { // Less than a day
        $hours = round($diff / 3600);
        return "$hours hour" . ($hours > 1 ? 's' : '') . " ago";
    } elseif ($diff < 604800) { // Less than a week
        $days = round($diff / 86400);
        return "$days day" . ($days > 1 ? 's' : '') . " ago";
    } elseif ($diff < 2419200) { // Less than a month
        $weeks = round($diff / 604800);
        return "$weeks week" . ($weeks > 1 ? 's' : '') . " ago";
    } elseif ($diff < 29030400) { // Less than a year
        $months = round($diff / 2419200);
        return "$months month" . ($months > 1 ? 's' : '') . " ago";
    } else {
        $years = round($diff / 29030400);
        return "$years year" . ($years > 1 ? 's' : '') . " ago";
    }
}
