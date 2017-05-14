<?php
session_start();
require_once ('utils/connection.php');
require_once('src/User.php');
require_once 'templates/header.html';
require_once 'templates/navbar_unlogged.html';
require_once 'utils/echo.php';

if(isset($_GET['action']) && ($_GET['action'] == 'logout')){
        User::logOut();
}

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $email = $connection->real_escape_string($_POST['email']);
    $password = $connection->real_escape_string($_POST['password']);    
    if(User::logIn($connection, $email, $password) == true){
        header("location: index.php");
    } 
}
require_once 'templates/login_form.html';

echoCenter("Pierwszy raz na Twitterze?<a href='register.php'> Zarejestruj się</a>");

require_once 'templates/footer.html';