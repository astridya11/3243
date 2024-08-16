<?php
require_once('../config.php');
require(CONTROLLER_PATH . "search_movies_controller.php")
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="movies_dashboard.css" />
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous" />
    <script src="https://code.jquery.com/jquery-1.12.4.min.js" integrity="sha256-ZosEbRLbNQzLpnKIkEdrPv7lOy9C27hHQ+Xp8a4MxAQ=" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        header{
            justify-content: space-between;
            align-items: center;
        }

        header nav {
            align-items: center;
            transition: 0.5s;
        }

        header nav ul {
            display: inline-block;
            font-size: 16px;
        }

        header nav ul li a {
            color: white;
            transition: all 300ms ease-in-out;
            font-size: 20px;
        }

        header nav ul li a:hover {
            color: red;
        }

        header nav ul li {
            margin-right: 20px;
            display: inline-block;
        }
    </style>
    <title>Search</title>
</head>

<body>
    <header>
        <nav>
            <ul id="menuitem">
                <li><a href="movies_dashboard.php">Movies Dashboard </a></li>
            </ul>
        </nav>
        <div class="subscribe flex">
            <form id="form" class="form" action="search_movies.php" method="POST">
                <input type="text" placeholder="Search" id="search" class="search" name="search" />
                <button type="submit" name="searchButton" class="searchButton">
                    <i id="searchbtn" class="fas fa-search"></i>
                </button>
            </form>

            <i id="playbtn" class="fas fa-user"></i>
        </div>
    </header>

    <main id="main">

        <?php foreach ($search_data as $item) : ?>

            <a href="movies_details.php echo urlencode($item['movieID']); ?>" class="movie">

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
        document.getElementById("playbtn").onclick = function() {
            window.location.href = "profile.php";
        };
    </script>

</body>

</html>