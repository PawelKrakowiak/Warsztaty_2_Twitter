<?php
session_start();
require_once 'utils/check_login.php';
require_once 'utils/connection.php';
require_once 'src/Tweet.php';
require_once 'src/User.php';
require_once 'src/Comment.php';

if(isset($_GET['id'])){
    $tId = $_GET['id'];
    $tweet = Tweet::loadTweetById($connection, $tId);
    
    $uId = $tweet->getUserId();
    $user = User::loadUserById($connection, $uId);
    
    $uName = $user->getUsername();
    $creationDate = $tweet->getCreationDate();
    $text = $tweet->getText();
    $comments = Comment::loadAllCommentsByTweetId($connection, $tId);
} else{
    die ("Błąd");
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $commText = $_POST['comment'];
    $time = date('Y-m-d H:i:s');
    Comment::addComment($connection, $time, $commText, $_SESSION['loggedUser'], $tId);
}


echo "<a href=" . '"' . "user_details.php?id=$uId" . '">' . "<h3>$uName</h3></a>"
       . "$creationDate . <br>"
       . "<a href=" . '"'. "post_details.php?id=$tId" . '">' . "$text</a><hr>";

echo "<h3>Komentarze: </h3>";

if($comments == []){
    echo "Na razie brak komentarzy. <br><br>";
} else{
    for ($i = 0; $i < count($comments); $i++) {
        $commId = $comments[$i]->getId();
        $commCreationDate = $comments[$i]->getCreationDate();
        $commText = $comments[$i]->getText();
        $commUserId = $comments[$i]->getUserId();
        $user = User::loadUserById($connection, $commUserId);
        $commUserName = $user->getUsername();
        echo "<a href=" . '"' . "user_details.php?id=$uId" . '">' . "$commUserName</a>"
           . "$commCreationDate . <br>"
           . "$commText <br> <br>";
    }
}


echo    "
        <form name='CommentAdd' action='post_details.php?id=$tId' method='POST'>
            <label>
                Napisz komentarz <br>
                <textarea name='comment'>
                    Twój komentarz...
                </textarea>
            </label>
            <input type='submit' value='Wyślij'>
        </form>
        ";
//<!--Wyświetlanie komentarzy do wpisu (autor jako klikalny link)-->
