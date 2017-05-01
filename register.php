<?php

require_once('utils/connection.php');
require_once('src/User.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (1 == 1) { //do zwalidowania
        if ($_POST['password'] == $_POST['confirmPassword']) {
            $email = $_POST['email'];
            $birthDate = $_POST['birthDate'];
            $gender = $_POST['gender'];
            $password = $_POST['password'];
            $userName = $_POST['username'];
            if(User::register($connection, $email, $password, $userName, $birthDate, $gender) == true){
                echo 'Gratulacje, udało się zarejestować. Możesz teraz się <a href="login.php">zalogować</a>';
            } else{
                echo 'Nie udało się zarejestrować, spróbuj ponownie.';
            }
        }else{
            echo "Wprowadzone hasła nie są identyczne!";
        }
    }
}

//Onsługa formularza do rejestracji
//jeśli udało się zapisać - zaloguj i przekieruj na stronę index.php
//$user->login();
//header("Location: index.php");

//jeśli nie udało się zapisać - wyświetl info, że podany adres email jest już zajęty

?>
<form name="register" action="register.php" method="POST">
    <input type="text" name="username" placeholder="Nazwa użytkownika"><br>
    <input type="email" name="email" placeholder="Adres e-mail"> <br>
    <input type="password" name="password" placeholder="Hasło"> <br>
    <input type="password" name="confirmPassword" placeholder="Potwierdzenie hasła"> <br>
    <label>Data urodzenia: <br>
        <input type="date" name="birthDate"> <br>
    </label>
    <label>Zaznacz płeć: <br>
        <input type="radio" name="gender" value="male" checked> Mężczyzna
        <input type="radio" name="gender" value="female"> Kobieta<br>
    </label>
    <input type="submit" value="Register">
</form>
