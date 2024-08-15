<?php
include('auth.php');
require('database.php');

// Check if user is logged in
if (!isset($_SESSION['userID'])) {
    header('Location: login.php'); // Redirect to login if not logged in
    exit;
}

// Retrieve userID from session
$userID = $_SESSION['userID'];

if (isset($_REQUEST['movieID'])) {
    $movieID = $_REQUEST['movieID']; // Retrieve movieID from request
} else {
    echo "Error: movieID not provided!";
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ratingID = 'rating' . date('YmdHis');
    $ratingStar = $_POST['ratingStar'];
    $ratingDescription = $_POST['ratingDescription'];

    // Insert rating into database
    $query = "INSERT INTO rating (ratingID, ratingDate, ratingTime, ratingDescription, ratingStar, userID, movieID)
              VALUES ('$ratingID', CURDATE(), NOW(), '$ratingDescription', '$ratingStar', '$userID', '$movieID')";

    if (mysqli_query($con, $query)) {
        header("Location: movies_details.php?movieID=" . urlencode($movieID)); // Redirect to the movie page
        exit;
    } else {
        echo "Error: " . mysqli_error($con);
    }
}
?>


<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel='stylesheet' href='https://fonts.googleapis.com/css?family=Poppins'>
    <link rel="stylesheet" href="rating_style.css">
    <title>Add Rating</title>
</head>
<body>
    <!-- header section -->
    <div class="header">
        <div class="header-left">
            <a href="movies_dashboard.php"><img src="image/logo-transparent.png"></a>
        </div>
        <div class="header-right">
            <a href="profile.php"><img src="image/profile-icon.png"></a>
        </div>
    </div>

    <!-- Add rating section -->
    <div class="rating-box">
        <h2>Add Your Rating</h2>
        <form method="POST" action="add_rating.php">
            <input type="hidden" name="movieID" value="<?php echo htmlspecialchars($movieID); ?>">

            <label for="ratingStar">Star Rating:</label>
            <div class="star-rating">
                <input type="radio" id="star5" name="ratingStar" value="5" required>
                <label for="star5">&#9733;</label>
                <input type="radio" id="star4" name="ratingStar" value="4">
                <label for="star4">&#9733;</label>
                <input type="radio" id="star3" name="ratingStar" value="3">
                <label for="star3">&#9733;</label>
                <input type="radio" id="star2" name="ratingStar" value="2">
                <label for="star2">&#9733;</label>
                <input type="radio" id="star1" name="ratingStar" value="1">
                <label for="star1">&#9733;</label>
            </div>

            <label for="ratingDescription">Your Review:</label>
            <textarea name="ratingDescription" id="ratingDescription" rows="5" placeholder="Write your review here..."></textarea>

            <button type="submit">Submit Review</button>
        </form>
    </div>
</body>
</html>
