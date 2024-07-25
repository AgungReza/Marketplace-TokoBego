<?php
// config.php

$servername = "localhost";
$username = "root";
$password = ""; // Default password for root user in Laragon is empty
$dbname = "TokoBego";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
