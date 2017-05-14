<?php
session_start();
require_once 'templates/header.html';
require_once 'templates/navbar.html';
require_once 'utils/check_login.php';
require_once 'utils/connection.php';
require_once 'src/Tweet.php';
require_once 'src/User.php';
require_once 'src/Comment.php';
require_once 'utils/echo.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $text = $_POST['tweet'];
    $time = date('Y-m-d H:i:s');
    if(Tweet::addTweet($connection, $time, $text, $_SESSION['loggedUser'])){
        echoCenter("Dodano nowego tweeta");
    }else{
        echoCenter("Błąd, nie udało się dodać tweeta.",1);
    }
}
$id = $_SESSION['loggedUser'];
$tweets = Tweet::loadAllTweets($connection);

require_once 'templates/tweet_add_form.html';

for ($i = 0; $i < count($tweets); $i++) {

    $id = $tweets[$i]->getId();
    $creationDate = $tweets[$i]->getCreationDate();
    $text = $tweets[$i]->getText();

    $uId = $tweets[$i]->getUserId();
    $user = User::loadUserById($connection, $uId);
    $uName = $user->getUsername();

    $commentsCounter = Comment::countAllCommentsByTweetId($connection, $id);
    
    echo "<div class='container text-center'>
            <div class='row'>
                <div class='col-md-12'>
                    <div class='well' style='background-color:gainsboro;'>
                        <div class='span12 text-right'>$creationDate</div>
                        <a href=" . '"' . "user_details.php?id=$uId" . '" style="text-decoration:none; color:tomato;">' . "<h2>$uName</h2></a>
                        <a href=" . '"' . "post_details.php?id=$id" . '" style="font-size:20px; text-decoration:none; color:dimgrey">' . "$text</a>
                        <div class='span12 text-right'>
                            <a href=" . '"' . "post_details.php?id=$id" . '" class="btn btn-primary">' . "Komentarze: $commentsCounter </a>
                        </div>
                    </div>
                </div>
            </div>  
        </div>";
}
require_once 'templates/footer.html';
