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

if(isset($_GET['profile'])){
    if ($_GET['profile'] == 'me') {
        $uId = $_SESSION['loggedUser'];
    }else{
        die("Błąd");
    }
} elseif(isset($_GET['id'])){
    $uId = $_GET['id'];
} else{
    die("Błąd");
}

$user = User::loadUserById($connection, $uId);
$tweets = Tweet::loadAllTweetsByUserId($connection, $uId);
$uName = $user->getUsername();
$uBirthday = substr($user->getBirthDate(), 8, 2).".".substr($user->getBirthDate(),5,2);


echo "<div class = 'container text-center'>
        <div class = 'row'>
            <div class = 'col-md-10'></div>
            <div class = 'col-md-2'>
                <h2 style='color: black; text-shadow: 1px 1px grey;'>$uName</h2>
            </div>
        </div>
    </div>";

if ($uId != $_SESSION['loggedUser']) {
    echo "<div class = 'container text-center'>
            <div class = 'row'>
                <div class = 'col-md-10'></div>
                <div class = 'col-md-2'>
                    <a href = 'send_message.php?id=$uId' class='btn btn-primary'>Napisz wiadomość </a><br>";
    if ($user->getGender() == 'male') {
        if ($user->checkBirthday() == 0) {
            echo    "<p> $uName ma dzisiaj urodziny, nie zapomnij wysłać mu wiadomości z życzeniami!</p><br>";
        }else{
            echo    "<p>Urodziny będzie obchodził $uBirthday, czyli za " . $user->checkBirthday() . " dni </p><br>";
        }          
    } else{
        if ($user->checkBirthday() == 0) {
            echo    "<p> $uName ma dzisiaj urodziny, nie zapomnij wysłać jej wiadomości z życzeniami!</p><br>";
        }else{
            echo    "<p>Urodziny będzie obchodziła $uBirthday, czyli za " . $user->checkBirthday() . " dni </p><br>";
        } 
    }
    echo "       
                </div>
            </div>
        </div>";
} else {
    echo "<div class = 'container text-center'>
            <div class = 'row'>
                <div class = 'col-md-10'></div>
                <div class = 'col-md-2'>
                    <a href='edit_user.php' class='btn btn-success'>Edytuj swoje dane</a>
                </div>
            </div>
        </div>";
}

echo "<div class = 'container text-center'>
            <div class = 'row'>
                <div class = 'col-md-5'></div>
                <div class = 'col-md-2'>
                    <h2 style='color: black; text-shadow: 1px 1px grey;'>Tweety:</h2>
                </div>
                <div class = 'col-md-5'></div>
            </div>
        </div>";

for ($i = 0; $i < count($tweets); $i++) {

    $id = $tweets[$i]->getId();
    $creationDate = $tweets[$i]->getCreationDate();
    $text = $tweets[$i]->getText();
    $commentsCounter = Comment::countAllCommentsByTweetId($connection, $id);

    echo "<div class = 'container text-center'>
            <div class = 'row'>
                <div class = 'col-md-12'>
                    <div class = 'well' style = 'background-color:gainsboro;'>
                        <div class = 'span12 text-right'>$creationDate</div>
                            <a href = " . '"' . "user_details.php?id=$uId" . '" style="text-decoration:none; color:tomato;">' . "<h2>$uName</h2></a>
                            <a href = " . '"' . "post_details.php?id=$id" . '" style="font-size:20px;text-decoration:none;color:dimgrey">' . "$text</a>
                        <div class = 'span12 text-right'>
                            <a href = " . '"' . "post_details.php?id=$id" . '" class="btn btn-primary">' . "Komentarze: $commentsCounter </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>";
}
require_once 'templates/footer.html';
