<?php
session_start();
require_once 'utils/check_login.php';
require_once 'utils/connection.php';
require_once 'src/Tweet.php';
require_once 'src/User.php';

if($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['id'])){
    $uId = $_GET['id'];
    $user = User::loadUserById($connection, $uId);
    $tweets = Tweet::loadAllTweetsByUserId($connection, $uId);
}

//SELECT p.*, COUNT(c.id) FROM post p 
//JOIN comment c ON c.post_id = p.id
//WHERE p.author_id = $_GET['id']
//GROUP BY p.id; - lista wpisów wraz z liczbą komentarzy

?>

<!--Formularz do wysyłania wiadomości do użytkownika-->

<?php
echo "Strona użytkownika " . $user->getUsername() . "<br>";

if($user->getGender() == 'male'){
    if($user->checkBirthday() == 0){
        echo "Wygląda na to, że " . $user->getUsername() . " ma dzisiaj urodziny, nie zapomnij wysłać mu wiadomości z życzeniami! <br>";
    } else{
    echo "Urodziny będzie obchodził za " . $user->checkBirthday() . " dni <br>";
    }
}
else{
    if($user->checkBirthday() == 0){
        echo "Wygląda na to, że " . $user->getUsername() . " ma dzisiaj urodziny, nie zapomnij wysłać jej wiadomości z życzeniami! <br>";
    } else{
    echo "Urodziny będzie obchodziła za " . $user->checkBirthday() . " dni <br>";
    }
}

echo "<h2> Tweety: </h2> <hr>";

for ($i = 0; $i < count($tweets); $i++) {
    $id = $tweets[$i]->getId();
    $creationDate = $tweets[$i]->getCreationDate();
    $text = $tweets[$i]->getText();
    $uId = $tweets[$i]->getUserId();
    $user = User::loadUserById($connection, $uId);
    $uName = $user->getUsername();
    echo "<a href=" . '"' . "user_details.php?id=$uId" . '">' . "<h3>$uName</h3></a>"
       . "$creationDate . <br>";
    echo "<a href=" . '"'. "post_details.php?id=$id" . '">' . "$text</a><hr>";
}


