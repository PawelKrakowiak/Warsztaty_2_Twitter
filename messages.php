<?php
session_start();
require_once 'utils/check_login.php';
require_once 'utils/connection.php';
require_once 'src/User.php';
require_once 'src/Message.php';

$id = $_SESSION['loggedUser'];

echo "Wybierz folder: ";
echo "<a href='messages.php?messages=received'>Odebrane</a>
      <a href='messages.php?messages=sent'>Wysłane</a><br><br>";

$receivedMessages = Message::loadAllMessagesByReceiverId($connection, $id);
$sentMessages = Message::loadAllMessagesBySenderId($connection, $id);
?>

<table id="received">
    <tr>
        <th>
            Nadawca
        </th>
        <th>
            Treść
        </th>
        <th>
            Data
        </th>
    </tr>
    <?php
    if ($_GET['messages'] == 'received') {
        echo "Odebrane wiadomości <br><br>";

        for ($i = 0; $i < count($receivedMessages); $i++) {
            $senderId = $receivedMessages[$i]->getSenderId();
            $messageId = $receivedMessages[$i]->getId();
            $sender = User::loadUserById($connection, $senderId);
            $senderName = $sender->getUsername();
            $text = mb_substr($receivedMessages[$i]->getText(), 0, 30);
            if(mb_strlen($receivedMessages[$i]->getText()) > 30){
                $text .= "...";
            }
            $time = $receivedMessages[$i]->getCreationDate();

            echo
            "<tr>
            <td>
                <a href='user_details.php?id=$senderId'>$senderName</a>
            </td>    
            <td>
                <a href='message.php?id=$messageId'>$text</a>
            </td>
            <td>
                $time
            </td>
        </tr>";
        }
    } else if ($_GET['messages'] == 'sent') {
        echo "Wysłane wiadomości <br><br>";

        for ($i = 0; $i < count($sentMessages); $i++) {
            $receiverId = $sentMessages[$i]->getReceiverId();
            $messageId = $sentMessages[$i]->getId();
            $receiver = User::loadUserById($connection, $receiverId);
            $receiverName = $receiver->getUsername();
            $text = mb_substr($sentMessages[$i]->getText(), 0, 30);
            if(mb_strlen($sentMessages[$i]->getText()) > 30){
                $text .= "...";
            }
            $time = $sentMessages[$i]->getCreationDate();
            

            echo
            "<tr>
            <td>
                <a href='user_details.php?id=$receiverId'>$receiverName</a>
            </td>    
            <td>
                <a href='message.php?id=$messageId'>$text</a>
            </td>
            <td>
                $time
            </td>
        </tr>";
        }
    }
    ?>
</table>