<?php
session_start();
require_once ('utils/connection.php');
require_once('src/User.php');

if(($_SERVER['REQUEST_METHOD'] == 'GET') && ($_GET['action'] == 'logout')){
        User::logOut();
}

echo "<h3> Aby korzystać z naszego serwisu, musisz się zalogować. </h3>";


if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $email = $connection->real_escape_string($_POST['email']);
    $password = $connection->real_escape_string($_POST['password']);    
    if(User::logIn($connection, $email, $password) == true){
        header("location: index.php");
    } 
}

//obsługa formularza logowania
//czy w bazie jest użytkownik o podanym emailu i haśle?
//jeśli tak - zaloguj
//jeśli nie - wyświetl komunikat
?>

<form name="login" action="login.php" method="POST">
    <input type="email" name="email" placeholder="Podaj adres e-mail">
    <input type="password" name="password" placeholder="Podaj hasło">
    <input type="submit" value="Login">
</form>

<a href="register.php">Nie posiadasz konta w naszym serwisie? Zarejestruj się!</a>