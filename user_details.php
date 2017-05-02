<?php
session_start();
require_once 'utils/check_login.php';
require_once 'utils/connection.php';
require_once 'src/Tweet.php';
require_once 'src/User.php';

if($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['id'])){
    $uId = $_GET['id'];
    $tweets = Tweet::loadAllTweetsByUserId($connection, $uId);
}

//SELECT p.*, COUNT(c.id) FROM post p 
//JOIN comment c ON c.post_id = p.id
//WHERE p.author_id = $_GET['id']
//GROUP BY p.id; - lista wpisów wraz z liczbą komentarzy

?>

<!--Formularz do wysyłania wiadomości do użytkownika-->

<?php

for ($i = 0; $i < count($tweets); $i++) {
    $id = $tweets[$i]->getId();
    $creationDate = $tweets[$i]->getCreationDate();
    $text = $tweets[$i]->getText();
    $uId = $tweets[$i]->getUserId();
    $user = User::loadUserById($connection, $uId);
    $uName = $user->getUsername();
    echo "Tweet użytkownika <a href=" . '"' . "user_details.php?id=$uId" . '">' . "$uName</a> Napisany: $creationDate . <br>";
    echo "<a href=" . '"'. "post_details.php?id=$id" . '">' . "$text</a><hr>";
}


