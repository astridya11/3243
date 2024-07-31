<?php
require("database.php"); // Ensure this file initializes $con for mysqli

// Fetch movie details
$movieID = 1; // Assuming movieID is hardcoded for now
$movieQuery = "SELECT * FROM movie WHERE movieID = $movieID";
$movieResult = mysqli_query($con, $movieQuery);

if ($movieResult) {
    $movie = mysqli_fetch_assoc($movieResult);
} else {
    echo "Error: " . mysqli_error($con);
    exit;
}

// Fetch user reviews
$reviewsQuery = "SELECT r.ratingStar, r.ratingDescription, r.ratingDate, u.userProfilePic, u.userName 
                 FROM rating r 
                 JOIN user u ON r.userID = u.userID 
                 WHERE r.movieID = $movieID";
$reviewsResult = mysqli_query($con, $reviewsQuery);

if (!$reviewsResult) {
    echo "Error: " . mysqli_error($con);
    exit;
}

// Fetch movie ratings for statistics
$ratingQuery = "SELECT ratingStar FROM rating WHERE movieID = $movieID";
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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Movie Details</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <a href="database.php" class="back-link"><i class="fas fa-angle-left"></i> Back</a><br><br>

    <div class="container">
        <div class="header">
            <h1>Movie Details</h1>
        </div>

        <!-- Statistic section -->
        <section class="statistic" id="statistic">
            <div class="movie-box-container">
                <div class="box">
                    <img src="mario.jpg" alt="Movie Image"> <!-- 这里要和movie part对一下URL -->
                </div>
            </div>

            <!-- Total statistic review -->
            <div class="review-box-container">
                <div class="details">
                    <h1><strong>Movie title:</strong> <?php echo htmlspecialchars($movie['title']); ?></h1>
                    <p><strong>Date released: </strong> <?php echo htmlspecialchars($movie['dateReleased']); ?></p>
                    <p><strong>Director: </strong> <?php echo htmlspecialchars($movie['director']); ?></p>
                    <p><strong>Cast: </strong> <?php echo htmlspecialchars($movie['cast']); ?></p>
                    <p><strong>Language: </strong> <?php echo htmlspecialchars($movie['language']); ?></p>
                    <p><strong>Synopsis: </strong> <?php echo htmlspecialchars($movie['synopsis']); ?></p>
                </div>
                <div class="rating-summary">
                    <h2><?php echo $averageRating; ?> <i class="fas fa-star"></i></h2>
                    <p><?php echo $totalReviews; ?> reviews</p>
                </div>
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
        </section>

        <!-- User review section -->
        <section class="userreview">
            <div class="heading">
                <h1>User's Reviews</h1>
                <a href="add_review.php?get_id=<?= htmlspecialchars($movieID); ?>" class="inline-btn" style="margin-top: 0;">Add Review</a>
            </div>

            <div class="box-container">
                <?php
                if ($reviewsResult) {
                    while ($fetch_review = mysqli_fetch_assoc($reviewsResult)) {
                        // Fetch user details for each review
                        $userQuery = "SELECT * FROM rating WHERE userID = " . intval($fetch_review['userID']);
                        $userResult = mysqli_query($con, $userQuery);
                        $fetch_user = mysqli_fetch_assoc($userResult);
                ?>
                        <div class="box">
                            <div class="user">
                                <?php if ($fetch_user['userProfilePic'] != '') { ?>
                                    <img src="uploaded_files/<?= htmlspecialchars($fetch_user['userProfilePic']); ?>" alt="">
                                <?php } else { ?>
                                    <h3><?= htmlspecialchars(substr($fetch_user['userName'], 0, 1)); ?></h3>
                                <?php } ?>
                                <div>
                                    <p><?= htmlspecialchars($fetch_user['userName']); ?></p>
                                    <span><?= htmlspecialchars($fetch_review['ratingDate']); ?></span>
                                </div>
                            </div>
                            <div class="ratings">
                                <?php
                                for ($i = 1; $i <= 5; $i++) {
                                    $ratingColor = ($i <= $fetch_review['ratingStar']) ? 'var(--main-color)' : 'var(--light-grey)';
                                    echo '<p style="background: ' . $ratingColor . ';">';
                                    echo '<i class="fas fa-star"></i> <span>' . htmlspecialchars($fetch_review['ratingStar']) . '</span></p>';
                                }
                                ?>
                            </div>
                            <h3 class="title"><?= htmlspecialchars($fetch_review['ratingDescription']); ?></h3>
                            <?php if ($fetch_review['ratingDescription'] != '') { ?>
                                <p class="description"><?= htmlspecialchars($fetch_review['ratingDescription']); ?></p>
                            <?php } ?>
                        </div>
                <?php
                    }
                } else {
                    echo '<p class="empty">No reviews added yet!</p>';
                }
                ?>
            </div>
        </section>

    </div>
</body>
</html>
