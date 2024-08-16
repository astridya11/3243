<?php
require_once('../config.php');
require(CONTROLLER_PATH . "movies_dashboard_controller.php")
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="movies_dashboard.css" />
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://code.jquery.com/jquery-1.12.4.min.js" integrity="sha256-ZosEbRLbNQzLpnKIkEdrPv7lOy9C27hHQ+Xp8a4MxAQ=" crossorigin="anonymous"></script>
    <title>Movies Dashboard</title>
</head>

<body>
    <header>
    <nav>
            <ul id="menuitem">
                <li><i id="refreshbtn" class="fa fa-refresh" aria-hidden="true" ></i></li>
            </ul>
        </nav>
        <div class="subscribe flex" style="align-items: center;">
            <form id="form" class="form" action="search_movies.php" method="POST">
                <input type="text" placeholder="Search" id="search" class="search" name="search" />
                <button type="submit" name="searchButton" class="searchButton">
                    <i id="searchbtn" class="fas fa-search"></i>
                </button>
            </form>
            <i id="playbtn" class="fas fa-user" style="margin: 0px 12px 0px;"></i>
        </div>
    </header>


    <main id="main">
        <?php foreach ($data as $item) : ?>
            <a href="movies_details.php?movieID=<?php echo urlencode($item['movieID']); ?>" class="movie">

                <img src="<?php echo $item['imageURL']; ?>" alt="<?php echo $item['title']; ?>">
                <div class="movie-info">
                    <h3><?php echo $item['title']; ?></h3>
                    <span class="<?php echo getColor($item['averageRating']); ?>"><?php echo $item['averageRating']; ?></span>
                </div>

                <div class="overview">
                    <h3>Overview</h3>
                    <?php echo $item['synopsis']; ?>
                </div>

            </a>

        <?php endforeach; ?>
    </main>

    <script>
        document.getElementById("refreshbtn").onclick = function() {
            window.location.reload();
        }; 
        document.getElementById("playbtn").onclick = function() {
            window.location.href = "profile.php";
        };

    </script>

</body>

</html>