<?php
require_once ('utils/connection.php');
require_once ('src/Tweet.php');
require_once('src/User.php');

//Sprawdź czy użytkownik jest zalogowany

//obsługa formularza dodawania wpisu

$tweets = Tweet::loadAllTweets($connection);

var_dump($tweets);

$id = 0;
$uId = 0;
$text = "";
$creationDate = "";


for($i = 0; $i<count($tweets); $i++){
    $id = $tweets[$i] -> getId();
    $creationDate = $tweets[$i] -> getCreationDate();
    $text = $tweets[$i] ->getText();
    $uId = $tweets[$i] ->getUserId();
    $user = User::loadUserById($connection, $uId);
    $uName = $user->getUsername();
    echo "Tweet użytkownika $uName Napisany: $creationDate . <br>";
    echo $text . "<hr>";
}

?>

<!--  Formualrz dodawania wpisu  -->

<!--  Link do wiadomości zalogowanego użytkownika  -->
<!--  Link do edycji danych zalogowanego użytkownika  -->

<!--  Lista wpisów (jako linki do post.php?id=xxx   -->
