<?php include('read_movies_details.php'); ?>
<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8" />
    <title><?php echo $row['title']; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="movies_details.css" />
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous" />
    <!-- Owl Carousel css-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" />
    <!-- Owl Carousel css-->

    <!-- jquery css-->
    <script src="https://code.jquery.com/jquery-1.12.4.min.js" integrity="sha256-ZosEbRLbNQzLpnKIkEdrPv7lOy9C27hHQ+Xp8a4MxAQ=" crossorigin="anonymous"></script>
    <!-- jquery css-->

    <style>
     .home {
    color: white;
    background-image: url(<?php echo $row['imageURL']; ?>);
    background-repeat: no-repeat;
    background-position: right;
    background-size: contain;
    height: 100vh;
    width: 100%;
}
    </style>

</head>

<body>
    <section class="home" id="title">
        <div class="headerbg">
            <header>
                <div class="container">
                    <div class="navbar flex1">
                        <nav>
                            <ul id="menuitem">
                                <li><a href="#title"><?php echo $row['title']; ?></a></li>
                                <li><a href="#ratings">Ratings</a></li>
                                <li><a href="#trailer">Trailer</a></li>
                                <li><a href="#feedback">Feedback</a></li>
                                <li><a href="movies_dashboard.php">More Movies</a></li>
                            </ul>
                        </nav>
                        <span class="fa fa-bars" onclick="menutoggle()"></span>

                        <div class="subscribe flex">
                            <i id="playbtn" class="fas fa-user"></i>
                        </div>
                    </div>
                </div>
            </header>

            <div class="home_content">
                <div class="container">
                    <div class="details_left">
                        <h1><?php echo $row['title']; ?></h1>

                        <div class="time flex">
                            <span><?php echo $row['duration']; ?></span>
                            <i class="fas fa-circle"></i>
                            <p><?php echo $row['dateReleased']; ?></p>
                            <i class="fas fa-circle"></i>
                            <p><?php echo $row['language']; ?></p>
                        </div>

                        <div class="time flex">
                            <?php
                            // Loop through the genres array and generate HTML
                            $count = count($genresArray);
                            foreach ($genresArray as $index => $genre) {
                                echo "<button>$genre</button>";
                                if ($index < $count - 1) {
                                    echo '<i class="fas fa-circle"></i>';
                                }
                            }
                            ?>
                        </div>

                        <div class="time">

                            <p>Overview</p>
                            <div><?php echo $row['synopsis']; ?></div>
                        </div>
                        <div class="time">
                            <p>Director: <span><?php echo $row['director']; ?></span></p>
                            <p>Cast: <span><?php echo $row['cast']; ?><span></p>
                        </div>

                        <form method="POST" class="button flex">
                            <input type="submit" name="watchedButton" class="watchedButton" value="WATCHED" />
                            <button type="submit" name="wishlistButton" class="wishlistButton">
                                <i class="fa fa-plus"> </i> WISHLIST
                            </button>
                            <?php if ($userRole == 'admin') { ?>
                                <input type="submit" name="updateButton" class="updateButton" value="UPDATE" />
                                <button type="submit" name="deleteButton" class="deleteButton"><i class="fa fa-minus"></i> DELETE</button>
                            <?php } ?>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </section>

    <script>
        var menuitem = document.getElementById("menuitem");
        menuitem.style.maxHeight = "0px";

        function menutoggle() {
            if (menuitem.style.maxHeight == "0px") {
                menuitem.style.maxHeight = "200px";
            } else {
                menuitem.style.maxHeight = "0px";
            }
        }

        window.addEventListener("scroll", function() {
            var header = document.querySelector("header");
            header.classList.toggle("sticky", window.scrollY > 50);
        });
    </script>

    <section class="new_realase rate" id="ratings">
        <div class="container">
            <div class="items">
                <div class="left"></div>
            </div>
        </div>
    </section>

    <section class="popular mtop" id="trailer">
        <div class="container">
            <div class="heading flex1">
                <h2>Trailers</h2>
                <a href="movies_dashboard.php"><button class="more_movies_btn">MORE MOVIES</button></a>
            </div>

            <div class="owl-carousel owl-theme">
                <?php foreach ($videoURLs as $key => $videoURL) : ?>
                    <div class="item">
                        <div class="box">
                            <div class="imgBox">
                                <iframe width="100%" height="100%" src="<?php echo getEmbedUrl($videoURL); ?>"></iframe>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Owl Carousel -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.js"></script>
    <!-- Owl Carousel -->

    <script>
        $(".owl-carousel").owlCarousel({
            loop: true,
            margin: 20,
            nav: true,
            dots: false,
            responsive: {
                411: {
                    items: 1,
                },
                768: {
                    items: 2,
                },
                1000: {
                    items: 4,
                },
            },
        });
    </script>

    <section class="new_realase top" id="feedback">
        <div class="container">
            <div class="owl-carousel owl-carousel2 owl-theme">
                <div class="items">
                    <div class="left">
                        <div class="img">
                            <img src="image/p2.jpg" alt="" />
                        </div>
                        <div class="heading">
                            <h2><span></span> NEW REALEASE</h2>
                            <h1>THE WARRIOR LIFE</h1>
                        </div>
                        <div class="time flex">
                            <label>R</label>
                            <i class="fas fa-circle"></i>
                            <span>1hrs 50mins</span>
                            <i class="fas fa-circle"></i>
                            <a class="flex1"><img src="https://img.icons8.com/color/95/000000/imdb.png" />
                                8.5</a>
                            <i class="fas fa-circle"></i>
                            <p>2021</p>
                            <i class="fas fa-circle"></i>
                            <button>Action</button>
                        </div>

                        <p>
                            Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do
                            eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut
                            enim ad minim veniam, quis nostrud exercitation ullamco laboris
                            nisi ut aliquip ex ea commodo consequat.
                        </p>

                        <div class="button flex">
                            <button class="btn">PLAY NOW</button>
                            <i id="playbtn" class="fas fa-play"></i>
                            <p>WATCH TRAILER</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        $(".owl-carousel2").owlCarousel({
            loop: true,
            margin: 20,
            dots: true,
            items: 1,
        });
    </script>

    <!-- paxi mate ko lai aata hai-->
    <script>
        $(".owl-carousel").owlCarousel({
            loop: true,
            margin: 20,
            nav: true,
            dots: false,
            responsive: {
                414: {
                    items: 1,
                },
                600: {
                    items: 2,
                },
                1000: {
                    items: 4,
                },
            },
        });
    </script>
    <i id="playbtn" class="fas fa-user"></i>

    <script>
        document.getElementById("playbtn").onclick = function() {
            window.location.href = "profile.php";
        };
    </script>

</body>

</html>