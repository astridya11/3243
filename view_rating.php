<?php
// include('auth.php');
require('database.php');

// Fetch ratings from database
$ratingQuery = "SELECT ratingStar FROM rating";
$ratingResult = mysqli_query($con, $ratingQuery);

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
?>


<!DOCTYPE html>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel='stylesheet' href='https://fonts.googleapis.com/css?family=Poppins'>
    <link rel="stylesheet" href="rating_style.css">
    <title>Rating</title>
</head>

<body>
    <!-- Header section -->
    <div class="header">
        <div class="header-left">
            <a href="movie.php"><img src="image/logo-transparent.png"></a>
        </div>
        <div class="header-right">
            <a href="profile.php"><img src="image/profile-icon.png"></a>
        </div>
    </div>

    <!-- Total rating review section -->
    <div class="container">
        <div class="rating-summary">
            <h2><img src="image/star.png"> <?php echo $averageRating; ?></h2>
            <p><?php echo $totalReviews; ?> reviews</p>

        </div>

        <!-- Statistic bar -->
        <div class="rating-bars">
            <?php foreach ($ratingCounts as $stars => $count) : ?>
                <div class="bar">
                    <div class="bar-name"><?php echo $stars; ?> star </div>
                    <div class="bar-container">
                        <div class="bar-fill" style="width: <?php echo ($totalReviews > 0 ? ($count / $totalReviews) * 100 : 0); ?>%;"></div>
                    </div>
                    <div class="bar-label"><?php echo $count; ?></div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- User review section -->
<div class="user-review-container">
    <div class="user-review-header">
        <h2>User's Reviews</h2>
        <button type="button" class="add-review" onclick="window.location.href='add_review.php'">Add Review</button>
    </div>
    <div class="user-review">
        <div class="user-info">
            <img src="image/avatar.png" class="user-image">
            <div>
                <p class="user-name">shaikh anas</p>
                <p class="review-date">2023-01-15</p>
            </div>
        </div>
        <div class="review-content">
            <p class="review-text">nice post</p>
            <p class="review-edited">edited</p>
            <div class="review-actions">
               
            </div>
        </div>
        <div class="review-rating">
            <span class="stars">★★★★★</span>
            <span class="rating-score">(5)</span>
            <span> <button class="edit-review">Edit Review</button>
            <button class="delete-review">Delete Review</button> </span>
        </div>
    </div>
</div>



</body>

</html>