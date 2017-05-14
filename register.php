<?php
require_once 'utils/connection.php';
require_once 'src/User.php';
require_once 'templates/header.html';
require_once 'templates/navbar_unlogged.html';
require_once 'utils/echo.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (1 == 1) { //do zwalidowania
        if ($_POST['password'] == $_POST['confirmPassword']) {
            $email = $_POST['email'];
            $birthDate = $_POST['birthDate'];
            $gender = $_POST['gender'];
            $password = $_POST['password'];
            $userName = $_POST['username'];
            if(User::register($connection, $email, $password, $userName, $birthDate, $gender) == true){
                echoCenter("Zarejestrowano użytkownika. Możesz teraz się <a href='login.php'> zalogować</a>");
            } else{
                echoCenter("Nie udało się zarejestrować, spróbuj ponownie.", 1);
            }
        }else{
                echoCenter("Podane hasła nie są identyczne.", 1);
        }
    }
}

require_once 'templates/register_form.html';
require_once 'templates/footer.html';
