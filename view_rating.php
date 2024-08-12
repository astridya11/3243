<?php
require('database.php');

// Fetch ratings and reviews from database
$ratingQuery = "SELECT ratingStar FROM rating WHERE movieID = ?";
$ratingStmt = mysqli_prepare($con, $ratingQuery);
mysqli_stmt_bind_param($ratingStmt, 'i', $movieID);
mysqli_stmt_execute($ratingStmt);
$ratingResult = mysqli_stmt_get_result($ratingStmt);
$movieID = isset($_GET['movieID']) ? trim($_GET['movieID']) : null;

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

// Fetch reviews
$reviewQuery = "SELECT r.ratingID, r.ratingTime, r.ratingDate, r.ratingDescription, r.ratingStar, u.userName, u.userProfilePic
                FROM rating r
                JOIN users u ON r.userID = u.userID
                WHERE r.movieID = ?";
$stmt = mysqli_prepare($con, $reviewQuery);
mysqli_stmt_bind_param($stmt, 'i', $movieID);
mysqli_stmt_execute($stmt);
$reviewResult = mysqli_stmt_get_result($stmt);
?>

<!DOCTYPE html>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel='stylesheet' href='https://fonts.googleapis.com/css?family=Poppins'>
    <link rel="stylesheet" href="rating_style.css">
    <title>Rating</title>
</head>

<body class="rating_body">


    <!-- Total rating review section -->
    <div class="rating_container">
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
            <button type="button" class="add-review" onclick=addRating()>Add Review</button>
        </div>

        <?php if ($reviewResult && mysqli_num_rows($reviewResult) > 0) : ?>
            <?php while ($review = mysqli_fetch_assoc($reviewResult)) : ?>
                <div class="user-review">
                    <div class="user-info">
                        <img src="image/<?php echo htmlspecialchars($review['userProfilePic']); ?>" class="user-image">
                        <div>
                            <p class="user-name"><?php echo htmlspecialchars($review['userName']); ?></p>
                            <span>
                                <p class="review-date"><?php echo htmlspecialchars($review['ratingDate']); ?></p>
                                <p class="review-time"><?php echo htmlspecialchars($review['ratingTime']); ?></p>
                            </span>

                        </div>
                    </div>
                    <div class="review-content">
                        <p class="review-text"><?php echo htmlspecialchars($review['ratingDescription']); ?></p>
                    </div>
                    <div class="review-rating">
                        <span class="stars"><?php echo str_repeat('★', $review['ratingStar']) . str_repeat('☆', 5 - $review['ratingStar']); ?></span>
                        <span class="rating-score">(<?php echo $review['ratingStar']; ?>)</span>
                        <span>
                            <button class="edit-review" onclick="window.location.href='edit_review.php?id=<?php echo $review['ratingID']; ?>'">Edit Review</button>
                            <button class="delete-review" onclick="window.location.href='delete_review.php?id=<?php echo $review['ratingID']; ?>'">Delete Review</button>
                        </span>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php endif; ?>
    </div>
    <script>
        function addRating() {
            window.location.href = "add_rating.php?movieID=<?php echo $movieID ?>";
        }
    </script>
</body>

</html>