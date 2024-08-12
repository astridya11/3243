<?php
include("auth.php");
require('database.php');

$currentUserID = $_SESSION['userID'];

$query = "SELECT movies.movieID, movies.title, movies.imageURL FROM watchedlist JOIN  movies ON watchedlist.movieID = movies.movieID
    WHERE watchedlist.userID = '$currentUserID'";
$result = mysqli_query($con, $query) or die(mysqli_error($con));
$rows = mysqli_num_rows($result);

if ($rows > 0) 
{
    $watchedlistData = mysqli_fetch_all($result, MYSQLI_ASSOC);
} 
else {
    $status = "It's empty here, record your movie journey!";
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Watched Movies</title>
    <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
    <style>
    * {box-sizing: border-box;}

    body { 
        margin: 0;
        font-family: 'Poppins';
        font-size: 15px;
        color: white;
        background-color: black;
    }

    /* navigation bar */
    .header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        background-color: black;
        padding: 0px 20px;
        height: 10vh;
    }

    .header a {
        color: white;
        text-align: center;
        text-decoration: none;
        font-size: 18px; 
        line-height: 25px;
        border-radius: 4px;
    }

    .header-left, .header-right {
        display: flex;
        align-items: center;
    }

    .header-left img,
    .header-right img {
        display: block;
    }

    .header-left img {
        height: 45px;
    }

    .header-right img {
        height: 25px;
    }

    /* title and status */
    h1 {
        margin-left: 20px;
    }

    .status {
        text-align: center;
        margin-top: 30vh;
        color: #f2b704;
    }

    /* movie list */
    .movieList {
        display: grid;
        gap: 10px;
        padding: 10px;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    }

    .movieList .movie {
        background-color: transparent;
        border-radius: 10px;
        overflow: auto;
        text-align: center;
        padding: 10px;
        text-decoration: none;
    }

    .movieList .movie img {
        max-width: 100%;
        max-height: 250px;
        border-radius: 10px;
    }

    .movieList .movie-title {
        margin-top: 10px;
        color: #f2b704;
    }
    </style>
</head>
<body>
    <!--navigation bar-->
    <div class="header">
        <div class="header-left">
            <a href="movies_dashboard.php"><img src="image/logo-transparent.png"></a>
        </div>
        <div class="header-right">
            <a href="profile.php"><img src="image/profile-icon.png"></a>
        </div>
    </div>

    <!--title and status-->
    <h1>Watched Movies</h1>
    <?php
    if (isset($status)) 
    {
        echo "<p class='status'>$status</p>";
    }
    ?>

    <!--movie list-->
    <div class="movieList">
        <?php 
        if (!empty($watchedlistData))
        {
            foreach ($watchedlistData as $movie)
            {
        ?>
                <a href="movies_details.php?movieID=<?php echo urlencode($movie['movieID']); ?>" class="movie">
                    <img src="<?php echo htmlspecialchars($movie['imageURL']); ?>" alt="<?php echo htmlspecialchars($movie['title']); ?>">
                    <div class="movie-title"><?php echo htmlspecialchars($movie['title']); ?></div>
                </a>
        <?php
            }
        }
        ?>
    </div>
</body>
</html>