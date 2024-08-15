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

if (!isset($_REQUEST['movieID'])) {
    echo "Error: Movie not found! (movieID not provided)";
    exit;
}

$movieID = $_REQUEST['movieID'];

// Check if the user has already rated the movie
$query = "SELECT ratingID FROM rating WHERE movieID = ? AND userID = ?";
$stmt = mysqli_prepare($con, $query);
mysqli_stmt_bind_param($stmt, 'ss', $movieID, $userID);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $ratingID);

if (!mysqli_stmt_fetch($stmt)) {
    echo "Error: You have not rated this movie yet!";
    exit;
}
mysqli_stmt_close($stmt);

// Delete the rating
$query = "DELETE FROM rating WHERE movieID = ? AND userID = ?";
$stmt = mysqli_prepare($con, $query);
mysqli_stmt_bind_param($stmt, 'ss', $movieID, $userID);

if (mysqli_stmt_execute($stmt)) {
    $success = true;
} else {
    $success = false;
    echo "Error: " . mysqli_error($con);
}
mysqli_stmt_close($stmt);
?>

<!DOCTYPE html>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel='stylesheet' href='https://fonts.googleapis.com/css?family=Poppins'>
    <link rel="stylesheet" href="rating_style.css">
    <title>Delete Rating</title>
    <script>
        window.onload = function() {
            <?php if (isset($success) && $success) : ?>
                alert('Your rating has been successfully deleted.');
                window.location.href = 'movies_details.php?movieID=<?php echo htmlspecialchars($movieID); ?>';
            <?php endif; ?>
        }
    </script>
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

    <!-- Delete rating section -->
    <div class="rating-box">
        <h2>Delete Rating</h2>
        <p>Processing your request...</p>
    </div>
</body>

</html>
