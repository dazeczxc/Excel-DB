<?php
    $host = "localhost";
    $username = "root";
    $password = "032918";
    $database = "test101";

    // Create DB Connection
    $con = mysqli_connect($host, $username, $password, $database);

    // Check connection
    if (!$con) {
        die("Connection failed: " . mysqli_connect_error());
    }
    //echo "Connected successfully";
?>


