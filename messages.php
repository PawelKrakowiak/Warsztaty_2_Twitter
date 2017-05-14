<?php
session_start();
require_once 'utils/check_login.php';
require_once 'utils/connection.php';
require_once 'src/User.php';
require_once 'src/Message.php';
require_once 'templates/header.html';
require_once 'templates/navbar.html';


$id = $_SESSION['loggedUser'];
$receivedMessages = Message::loadAllMessagesByReceiverId($connection, $id);
$sentMessages = Message::loadAllMessagesBySenderId($connection, $id);
?>
<br><br>
<div class="container-fluid">
    <div class="text-centered">
            <table class ="table-striped text-centered" id="messages" style="border:solid dimgrey; border-radius: 5px; width: 50%; margin: auto; float: none;">
                <tr>
                    <th colspan="4" style="text-align: center">
                        <?php
                            if($_GET['messages'] == 'received'){
                                echo "Odebrane wiadomości";
                            }elseif($_GET['messages'] == 'sent'){
                                echo "Wysłane wiadomości";
                            }
                        ?>
                    </th>
                </tr>
                
                <tr>
                    <th>
                        <?php
                        if($_GET['messages'] == 'received'){
                            echo "Nadawca";
                        }elseif($_GET['messages'] == 'sent'){
                            echo "Odbiorca";
                        }
                        ?>
                    </th>
                    <th>
                        Treść
                    </th>
                    <th>
                        Data
                    </th>
                        <?php
                        if($_GET['messages'] == 'received'){
                            echo "     
                                <th>
                                    Status
                                </th>";
                        } ?>
                </tr>
                <?php
                if ($_GET['messages'] == 'received') {
                    for ($i = 0; $i < count($receivedMessages); $i++) {
                        $senderId = $receivedMessages[$i]->getSenderId();
                        $messageId = $receivedMessages[$i]->getId();
                        $sender = User::loadUserById($connection, $senderId);
                        $senderName = $sender->getUsername();
                        $text = mb_substr($receivedMessages[$i]->getText(), 0, 30);
                        if (mb_strlen($receivedMessages[$i]->getText()) > 30) {
                            $text .= "...";
                        }
                        $time = $receivedMessages[$i]->getCreationDate();

                        echo"
                            

                            <tr>
                                <td>
                                    <a href='user_details.php?id=$senderId'>$senderName</a>
                                </td>    
                                <td>
                                    <a href='message.php?id=$messageId'>$text</a>
                                </td>
                                <td>
                                    $time
                                </td>
                                <td>";
                        if($receivedMessages[$i]->getIsRead()){
                            echo "Przeczytane<span class='glyphicon glyphicon-ok'></span>";
                        }else{
                            echo "Nieprzeczytane<span class='glyphicon glyphicon-remove'></span>";
                        }
                        echo"
                            </tr>";
                    }
                } else if ($_GET['messages'] == 'sent') {
                    for ($i = 0; $i < count($sentMessages); $i++) {
                        $receiverId = $sentMessages[$i]->getReceiverId();
                        $messageId = $sentMessages[$i]->getId();
                        $receiver = User::loadUserById($connection, $receiverId);
                        $receiverName = $receiver->getUsername();
                        $text = mb_substr($sentMessages[$i]->getText(), 0, 30);
                        if (mb_strlen($sentMessages[$i]->getText()) > 30) {
                            $text .= "...";
                        }
                        $time = $sentMessages[$i]->getCreationDate();


                        echo"
                            <tr>
                                <td>
                                    <a href='user_details.php?id=$receiverId'>$receiverName</a>
                                </td>    
                                <td>
                                    <a href='message.php?id=$messageId'>$text</a>
                                </td>
                                <td>
                                    $time
                                </td>
                                <td>
                            </tr>"; 
                    }
                } else{
                    echo "Błąd <br>";
                }
                ?>
            </table>
    </div>
</div>
<?php
require_once 'templates/footer.html';
?>