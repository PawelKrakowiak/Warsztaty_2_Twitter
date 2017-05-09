<?php
session_start();
require_once 'utils/check_login.php';
require_once 'utils/connection.php';
require_once 'src/User.php';

echo "<a href='login.php?action=logout'>Wyloguj się</a><br><br>";
$id = $_SESSION['loggedUser'];
    $user = User::loadUserById($connection, $id);
    if ($_POST) {
        if ($_POST['password'] == $_POST['confirmPassword']) {
            $user->setUsername($_POST['username']);
            $user->setHashedPassword($_POST['password']);
            $user->setBirthDate($_POST['birthDate']);
            $user->setGender($_POST['gender']);
            if($user->saveToDB($connection) == true){
                echo "Zmodyfikowano dane użytkownika";
            } else{
                "Błąd, nie udało się edytować danych";
            }
        } else {
            echo "Podane hasła nie są identyczne. <br>";
        }
    }
    if ($_GET['action'] == 'delete') {
        var_dump($user);
        if ($user->delete($connection) == true) {
            echo "Usunięto użytkownika";
            unset($_SESSION['loggedUser']);
        } else {
            echo "Błąd, nie udało się usunąć użytkownika.";
        }
    }

echo "<a href='edit_user.php?action=delete'>Usuń konto</a><br>";
?>

<form action="edit_user.php" method='POST' name="edit_user">
    <legend><strong>Edytuj swoje dane użytkownika</strong><br>
        <input type="text" name="username" placeholder="Nazwa użytkownika" required><br>
        <input type="password" name="password" placeholder="Hasło" required> <br>
        <input type="password" name="confirmPassword" placeholder="Potwierdzenie hasła" required> <br>
        <label>Data urodzenia: <br>
            <input type="date" name="birthDate" required> <br>
        </label>
        <label>Zaznacz płeć: <br>
            <input type="radio" name="gender" value="male" checked> Mężczyzna
            <input type="radio" name="gender" value="female"> Kobieta<br>
        </label>
        <input type="submit" value="Edytuj">
    </legend>
</form>
