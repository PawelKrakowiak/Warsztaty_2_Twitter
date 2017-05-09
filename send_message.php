<?php
session_start();
require_once 'utils/check_login.php';
require_once 'utils/connection.php';
require_once 'src/User.php';
require_once 'src/Message.php';

$receiverId = "";
$receiverName = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $senderId = $_SESSION['loggedUser'];
    $sender = User::loadUserById($connection, $senderId);
    $senderName = $sender->getUsername();
    if ($senderName == $_POST['receiver']) {
        echo "Nie można wysłać wiadomości do samego siebie! <br>";
    } else {
        $receiverName = $connection->real_escape_string($_POST['receiver']);
        $receiver = User::loadUserByUsername($connection, $receiverName);
        $receiverId = $receiver->getId();
        $time = date("Y-m-d H:i:s");
        $text = $connection->real_escape_string($_POST['text']);
        Message::addMessage($connection, $time, $text, $senderId, $receiverId);
    }
}

if (isset($_GET['id'])) {
    $receiverId = $_GET['id'];
    $receiver = User::loadUserById($connection, $receiverId);
    $receiverName = $receiver->getUsername();
}
?>

<form action='send_message.php' method='POST' name='send_message'>
    <legend>Napisz wiadomość <br>
        <label> Odbiorca <br>
            <input type="text" name='receiver' <?php echo "value='$receiverName'" ?> required><br>
        </label>
        <textarea name="text">
            Wpisz treść wiadomości
        </textarea> <br>
        <input type="submit" value="Wyślij"> <br>
    </legend>
</form>
