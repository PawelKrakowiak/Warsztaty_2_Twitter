<?php

session_start();
require_once 'utils/check_login.php';
require_once 'utils/connection.php';
require_once 'src/Tweet.php';
require_once 'src/User.php';
require_once 'src/Comment.php';
require_once 'templates/header.html';
require_once 'templates/navbar.html';
require_once 'utils/echo.php';

if (isset($_GET['id'])) {
    $tId = $_GET['id'];
    $tweet = Tweet::loadTweetById($connection, $tId);

    $uId = $tweet->getUserId();
    $user = User::loadUserById($connection, $uId);

    $uName = $user->getUsername();
    $creationDate = $tweet->getCreationDate();
    $text = $tweet->getText();
    $comments = Comment::loadAllCommentsByTweetId($connection, $tId);
} else {
    die("Błąd");
}

echo "<div class='container text-center'>
            <div class='row'>
                <div class='col-md-12'>
                    <div class='well' style='background-color:gainsboro;'>
                        <div class='span12 text-right'>$creationDate</div>
                        <a href=" . '"' . "user_details.php?id=$uId" . '" style="text-decoration:none; color:tomato;">' . "<h2>$uName</h2></a>
                        <p style=font-size:20px; text-decoration:none; color:dimgrey> $text</p>
                    </div>
                </div>
            </div>  
        </div>";
echo "<div class='container'>
                <form name='commAdd' action='post_details.php?id=$tId' method='POST' id='commAdd'>
                    <label for='tweetAdd'>Napisz komentarz</label>
                    <textarea class='form-control' rows=3 name='comment' maxlength='60'></textarea>
                    <button type='submit' class='btn btn-success'>
                        <i class='icon-circle-arrow-right icon-large'></i> Dodaj
                    </button>
                </form>
            </div>
            <br>";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $commText = $_POST['comment'];
    $time = date('Y-m-d H:i:s');
    if (Comment::addComment($connection, $time, $commText, $_SESSION['loggedUser'], $tId)) {
        echoCenter("Dodano nowy komentarz");
        header("refresh: 0;");
    }else{
        echoCenter("Błąd, nie udało się dodać komentarza", 1);
    }
}
echo   "<div class='container text-center' style='background-color:steelblue'>
            <p><h3>Komentarze:</h3><p>";

if ($comments == []) {
    echoCenter("Na razie brak komentarzy. Twój może być pierwszy!");
} else {
    for ($i = 0; $i < count($comments); $i++) {
        $commId = $comments[$i]->getId();
        $commCreationDate = $comments[$i]->getCreationDate();
        $commText = $comments[$i]->getText();
        $commUserId = $comments[$i]->getUserId();
        $user = User::loadUserById($connection, $commUserId);
        $commUserName = $user->getUsername();

        echo "  <div class='row'>
                    <div class='col-md-12'>
                        <div class='well' style='background-color:gainsboro;'>
                            <div class='span12 text-right'>$commCreationDate</div>
                            <a href=" . '"' . "user_details.php?id=$commUserId" . '" style="text-decoration:none; color:tomato;">' . "<h2>$commUserName</h2></a>
                            <p style=font-size:20px; text-decoration:none; color:dimgrey> $commText</p>
                        </div>
                    </div>
                </div>";
    }
}
echo "</div>";
require_once 'templates/footer.html';