<?php
session_start();
require_once 'utils/check_login.php';
require_once 'utils/connection.php';
require_once 'src/User.php';
require_once 'src/Message.php';
require_once 'templates/header.html';
require_once 'templates/navbar.html';

$receiverId = "";
$receiverName = "";

if (isset($_GET['id'])) {
    $receiverId = $_GET['id'];
    $receiver = User::loadUserById($connection, $receiverId);
    $receiverName = $receiver->getUsername();
}
?>
<div class="container-fluid">
    <form class="form-horizontal" role="form" method="post" action="send_message.php" id="send_message">
        <legend style='text-align: center'>Napisz wiadomość</legend>
            <div class="form-group">
                    <label for="name" class="col-sm-3 control-label">Odbiorca</label>
                    <div class="col-sm-6">
                            <input type="text" class="form-control" id="name" name="receiver" <?php echo "value='$receiverName'"?> required>
                    </div>
            </div>
            <div class="form-group">
                    <label for="message" class="col-sm-3 control-label">Wpisz treść wiadomości</label>
                    <div class="col-sm-6">
                            <textarea class="form-control" rows="8" name="text"></textarea>
                    </div>
            </div>
            <div class="form-group">
                    <div class="col-sm-6 col-sm-offset-3">
                            <input id="submit" name="submit" type="submit" value="Wyślij" class="btn btn-success">
                    </div>
            </div>
    </form>
</div>
<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $senderId = $_SESSION['loggedUser'];
    $sender = User::loadUserById($connection, $senderId);
    $senderName = $sender->getUsername();
    if ($senderName == $_POST['receiver']) {
            echoCenter("Nie można wysłać wiadomości do samego siebie", 1);
    } else {
        $receiverName = $connection->real_escape_string($_POST['receiver']);
        $receiver = User::loadUserByUsername($connection, $receiverName);
        $receiverId = $receiver->getId();
        $time = date("Y-m-d H:i:s");
        $text = $connection->real_escape_string($_POST['text']);
        if(Message::addMessage($connection, $time, $text, $senderId, $receiverId)){
            echoCenter("Wysłano wiadomość");
        }else{
            echoCenter("Błąd, nie udało się wysłać wiadomości", 1);
        }
    }
}
require_once 'templates/footer.html';
