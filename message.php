<?php
session_start();
require_once 'utils/check_login.php';
require_once 'utils/connection.php';
require_once 'src/User.php';
require_once 'src/Message.php';
require_once 'templates/header.html';
require_once 'templates/navbar.html';

$messageId = $_GET['id'];

$message = Message::loadMessageById($connection, $messageId);
$senderId = $message->getSenderId();
$receiverId = $message->getReceiverId();
$senderName = User::loadUserById($connection, $senderId)->getUsername();
$receiverName = User::loadUserById($connection, $receiverId)->getUsername();
$text = $message->getText();
$time = $message->getCreationDate();

echo "
    <div class='container-fluid'>
        <div class='text-centered'>
            <table class ='table-striped text-centered' id='message' style='border:solid dimgrey; border-radius: 5px; width: 50%; margin: auto; float: none;'>
                <tr>
                    <th>
                        Nadawca&nbsp 
                    </th>
                    <th>
                        Odbiorca&nbsp
                    </th>
                    <th>
                        Data&nbsp
                    </th>
                </tr>
                <tr>
                    <td>
                        <a href='user_details.php?id=$senderId'>$senderName</a>
                    </td>   
                    <td>
                        <a href='user_details.php?id=$receiverId'>$receiverName</a>
                    </td>  
                    <td>
                        $time
                    </td>
                </tr>
                <tr>
                    <th colspan='3'>
                        Treść
                    </th>
                </tr>
                <tr>
                    <td colspan='3'>
                        $text
                    </td>
                </tr>
            </table>
        </div>
    </div>
    ";
require_once 'templates/footer.html';
if($senderId != $_SESSION['loggedUser']){
    $message->Read($connection);
}
