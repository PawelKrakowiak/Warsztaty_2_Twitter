<?php
$baseName = "warsztat";
$serverName = "localhost";
$userName = "root";
$password = "coderslab";

$connection = new mysqli($serverName, $userName, $password, $baseName);

if($connection->connect_error){
    die("Błąd: " . $conn->connect_error);
}

