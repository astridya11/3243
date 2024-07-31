<?php
// host, username, password, database name, port, socket
$con = mysqli_connect("localhost",  "root", "", "3243");

// return the error code from the last connection
if (mysqli_connect_errno())
{
    echo "Failed to connect to mySQL: " . mysqli_connect_error();
}
?>