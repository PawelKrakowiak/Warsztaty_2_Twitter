<?php
session_start();
require_once ('utils/check_login.php');
require_once ('utils/connection.php');
require_once ('src/Tweet.php');
require_once('src/User.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $text = $_POST['tweet'];
    $time = date('Y-m-d H:i:s', time());
    Tweet::addTweet($connection, $time, $text, $_SESSION['loggedUser']);
}

echo "<a href='login.php?action=logout'>Wyloguj się</a><br><br>";

echo "Witaj, " . $_SESSION["loggedUserName"] . " :) <br><br>";

$tweets = Tweet::loadAllTweets($connection);
?>

<form name="tweetAdd" action="index.php" method="POST">
    <label>
        Napisz tweeta <br>
        <textarea name="tweet">
            Napisz cośtam cośtam
        </textarea>
    </label>
    <input type="submit" value="Wyślij">
</form>


<!--  Link do wiadomości zalogowanego użytkownika  -->
<!--  Link do edycji danych zalogowanego użytkownika  -->

<!--  Lista wpisów (jako linki do post.php?id=xxx   -->


<?php
for ($i = 0; $i < count($tweets); $i++) {
    $id = $tweets[$i]->getId();
    $creationDate = $tweets[$i]->getCreationDate();
    $text = $tweets[$i]->getText();
    $uId = $tweets[$i]->getUserId();
    $user = User::loadUserById($connection, $uId);
    $uName = $user->getUsername();
    echo "Tweet użytkownika $uName Napisany: $creationDate . <br>";
    echo $text . "<hr>";
}
