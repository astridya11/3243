<?php
    require_once ('../config.php');
    require (CONTROLLER_PATH."processwishlist.php");
?>

<!DOCTYPE html>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Wishlist</title>
    <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
    <link href="header.css" rel="stylesheet">

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: 'Poppins';
            font-size: 15px;
            color: white;
            background-color: black;
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
            /* font-size: 16px; */
            /* font-weight: bold; */
            color: #f2b704;
        }
    </style>
</head>

<body>
    <!--navigation bar-->
    <div class="header">
        <div class="header-left">
            <a href="movies_dashboard.php"><img src="../model/image/logo-transparent.png"></a>
        </div>
        <div class="header-right">
            <a href="profile.php"><img src="../model/image/profile-icon.png"></a>
        </div>
    </div>

    <!--title and status-->
    <h1>Wishlist</h1>
    <?php
    if (isset($status)) {
        echo "<p class='status'>$status</p>";
    }
    ?>

    <!--movie list-->
    <div class="movieList">
        <?php
        if (!empty($wishlistData)) {
            foreach ($wishlistData as $movie) {
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