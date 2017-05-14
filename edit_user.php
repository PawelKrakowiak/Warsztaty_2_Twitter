<?php
session_start();
require_once 'utils/check_login.php';
require_once 'utils/connection.php';
require_once 'src/User.php';
require_once 'templates/header.html';
require_once 'templates/navbar.html';
require_once 'utils/echo.php';

$id = $_SESSION['loggedUser'];
    $user = User::loadUserById($connection, $id);
    if ($_POST) {
        if ($_POST['password'] == $_POST['confirmPassword']) {
            $user->setUsername($connection->real_escape_string($_POST['username']));
            $user->setHashedPassword($connection->real_escape_string($_POST['password']));
            $user->setBirthDate($connection->real_escape_string($_POST['birthDate']));
            $user->setGender($connection->real_escape_string($_POST['gender']));
            if($user->saveToDB($connection) == true){
                echoCenter("Zmodyfikowano dane użytkownika");
            } else{
                echoCenter("Błąd, nie udało się edytować danych", 1);
            }
        } else {
            echoCenter("Podane hasła nie są identyczne. <br>", 1);
        }
    }
    if (isset($_GET['action']) && $_GET['action'] == 'delete') {
                echoCenter("Czy na pewno chcesz usunąć konto?
                           <a href='edit_user.php?action=remove' class='btn btn-danger'>Tak, usuń konto</a>
                           <a href='edit_user.php' class = 'btn btn-success'>Nie, wróć do edycji profilu</a>");
    }
    if (isset($_GET['action']) && $_GET['action'] == 'remove'){
        if ($user->delete($connection)) {
                echoCenter("Usunięto użytkownika");
                unset($_SESSION['loggedUser']);
        } else {
                echoCenter("Błąd, nie udało się usunąć użytkownika", 1);
        }
    }
require_once 'templates/edit_user_form.html';
require_once 'templates/footer.html';

