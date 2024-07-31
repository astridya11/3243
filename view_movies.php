<?php
include("auth.php");
require('database.php');
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>View Product Records</title>
</head>

<body>
    <p><a href="index.php">Home Page</a></p>
    | <a href="create_movies.php">Insert New Movies</a>
    | <a href="logout.php">Logout</a></p>
    <h2>View Movie Records</h2>
    <table width="100%" border="1" style="border-collapse:collapse;">
        <thead>
            <tr>
                <th><strong>No.</strong></th>
                <th><strong>Title</strong></th>
                <th><strong>Date Released</strong></th>
                <th><strong>Duration</strong></th>
                <th><strong>Genre</strong></th>
                <th><strong>Language</strong></th>
                <th><strong>Country</strong></th>
                <th><strong>Director</strong></th>
                <th><strong>Cast</strong></th>
                <th><strong>Synopsis</strong></th>
                <th><strong>Image</strong></th>
                <th><strong>Trailer 1</strong></th>
                <th><strong>Trailer 2</strong></th>
                <th><strong>Trailer 3</strong></th>
                <th><strong>Trailer 4</strong></th>
                <th><strong>Trailer 5</strong></th>
                <th><strong>Trailer 6</strong></th>
                <th><strong>Trailer 7</strong></th>
                <th><strong>Trailer 8</strong></th>
                <th><strong>Trailer 9</strong></th>
                <th><strong>Trailer 10</strong></th>
                <th><strong>Edit</strong></th>
                <th><strong>Delete</strong></th>
            </tr>
        </thead>
        <tbody>
            <?php
            $count = 1;
            $sel_query = "SELECT * FROM movies ORDER BY movieID desc;";
            $result = mysqli_query($con, $sel_query);

            while ($row = mysqli_fetch_assoc($result)) {
            ?>
                <!-- the count here is not equaivalent to the id in database -->
                <tr>
                    <td align="center"><?php echo $count; ?></td>
                    <td align="center"><?php echo $row["title"]; ?></td>
                    <td align="center"><?php echo $row["dateReleased"]; ?></td>
                    <td align="center"><?php echo $row["duration"]; ?></td>
                    <td align="center"><?php echo $row["genre"]; ?></td>
                    <td align="center"><?php echo $row["language"]; ?></td>
                    <td align="center"><?php echo $row["country"]; ?></td>
                    <td align="center"><?php echo $row["director"]; ?></td>
                    <td align="center"><?php echo $row["cast"]; ?></td>
                    <td align="center"><?php echo $row["synopsis"]; ?></td>
                    <td align="center"><?php echo $row["imageURL"]; ?></td>
                    <td align="center"><?php echo $row["videoURL_1"]; ?></td>
                    <td align="center"><?php echo $row["videoURL_2"]; ?></td>
                    <td align="center"><?php echo $row["videoURL_3"]; ?></td>
                    <td align="center"><?php echo $row["videoURL_4"]; ?></td>
                    <td align="center"><?php echo $row["videoURL_5"]; ?></td>
                    <td align="center"><?php echo $row["videoURL_6"]; ?></td>
                    <td align="center"><?php echo $row["videoURL_7"]; ?></td>
                    <td align="center"><?php echo $row["videoURL_8"]; ?></td>
                    <td align="center"><?php echo $row["videoURL_9"]; ?></td>
                    <td align="center"><?php echo $row["videoURL_10"]; ?></td>
                    <td align="center">
                        <a href="update_movies.php?movieID=<?php echo $row["movieID"]; ?>">Update</a>
                    </td>
                    <td align="center">
                        <a href="delete_movies.php?movieID=<?php echo $row["movieID"]; ?>" onclick="return confirm('Are you sure you want to delete this product record?')">Delete</a>
                    </td>
                </tr>
            <?php $count++;
            } ?>
        </tbody>
    </table>
</body>

</html>