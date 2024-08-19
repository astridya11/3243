<?php 
require_once('../config.php');
require(CONTROLLER_PATH . 'auth.php');
require(CONTROLLER_PATH.'delete_rating_logic.php');
require(MODEL_PATH . 'database.php');
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
            <a href="movies_dashboard.php"><img src="../model/image/logo-transparent.png"></a>
        </div>
        <div class="header-right">
            <a href="profile.php"><img src="../model/image/profile-icon.png"></a>
        </div>
    </div>

    <!-- Delete rating section -->
    <div class="rating-box">
        <h2>Delete Rating</h2>
        <p>Processing your request...</p>
    </div>
</body>

</html>
