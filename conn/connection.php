<?php
$baseName = "warsztat";
$serverName = "localhost";
$userName = "root";
$password = "coderslab";

$conn = new mysqli($serverName, $userName, $password, $baseName);

if($conn->connect_error){
    die("Błąd: " . $conn->connect_error);
}

