<?php
session_start();
require_once 'utils/check_login.php';
require_once 'utils/connection.php';
require_once 'src/User.php';
require_once 'src/Message.php';

$messageId = $_GET['id'];

$sql = "SELECT * FROM Messages WHERE id = $messageId";

$result = $connection->query($sql);

$message = $result->fetch_assoc();
$senderId = $message['sender_id'];
$senderName = User::loadUserById($connection, $senderId)->getUsername();
$receiverId = $message['receiver_id'];
$receiverName = User::loadUserById($connection, $receiverId)->getUsername();
$text = $message['text'];
$time = $message['creation_date'];

echo "
    <table id='message'>
        <tr>
            <th>
                Nadawca
            </th>
            <th>
                Odbiorca
            </th>
            <th>
                Treść
            </th>
            <th>
                Data
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
                $text
            </td>
            <td>
                $time
            </td>
        </tr>
    </table>
    ";
