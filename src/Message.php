<?php

class Message {

    private $id;
    private $senderId;
    private $receiverId;
    private $text;
    private $creationDate;
    private $isRead;

    public function __construct() {
        $this->id = -1;
        $this->senderId = "";
        $this->receiverId = "";
        $this->text = "";
        $this->creationDate = "";
        $this->isRead = 0;
    }

    public function getId() {
        return $this->id;
    }

    public function getSenderId() {
        return $this->senderId;
    }

    public function getReceiverId() {
        return $this->receiverId;
    }

    public function getText() {
        return $this->text;
    }

    public function getCreationDate() {
        return $this->creationDate;
    }

    public function getIsRead() {
        return $this->isRead;
    }

    public function setSenderId($senderId) {
        $this->senderId = $senderId;
        return $this;
    }

    public function setReceiverId($receiverId) {
        $this->receiverId = $receiverId;
        return $this;
    }

    public function setText($text) {
        $this->text = $text;
        return $this;
    }

    public function setCreationDate($creationDate) {
        $this->creationDate = $creationDate;
        return $this;
    }

    public function setIsRead($isRead) {
        $this->isRead = $isRead;
        return $this;
    }

    public function saveToDB(mysqli $connection) {
        if ($this->id == -1) {
            $sql = "INSERT INTO Messages (sender_id, receiver_id, creation_date, text, is_read)
                    VALUES($this->senderId, $this->receiverId, '$this->creationDate', '$this->text', '$this->isRead')";
            $result = $connection->query($sql);
            if ($result == true) {
                $this->id = $connection->insert_id;
                return $result;
            } else {
                echo $connection->error;
            }
        } else {
            $sql = "UPDATE Messages SET sender_id = '$this->senderId', reciver_id = '$this->receiverId', text = '$this->text', "
                    . "creation_date = '$this->creationDate', is_read = '$this->isRead' WHERE id = $this->id";
            $result = $connection->query($sql);
            if ($result == true) {
                return $result;
            }
        }
        return $result;
    }

    static public function loadMessageById(mysqli $connection, $id) {
        $sql = "SELECT * FROM Messages WHERE id = $id";
        $result = $connection->query($sql);
        if ($result == true && $result->num_rows == 1) {
            $row = $result->fetch_assoc();
            $loadedMessage = new Message();
            $loadedMessage->id = $row['id'];
            $loadedMessage->senderId = $row['sender_id'];
            $loadedMessage->receiverId = $row['receiver_id'];
            $loadedMessage->text = $row['text'];
            $loadedMessage->creationDate = $row['creation_date'];
            $loadedMessage->isRead = $row['is_read'];

            return $loadedMessage;
        }
        return null;
    }

    static public function loadAllMessagesBySenderId(mysqli $connection, $senderId) {
        $sql = "SELECT * FROM Messages WHERE sender_id = $senderId ORDER BY creation_date DESC";
        $ret = [];
        $result = $connection->query($sql);
        if ($result == true && $result->num_rows != 0) {
            foreach ($result as $row) {
                $loadedMessage = new Message();
                $loadedMessage->id = $row['id'];
                $loadedMessage->senderId = $row['sender_id'];
                $loadedMessage->receiverId = $row['receiver_id'];
                $loadedMessage->text = $row['text'];
                $loadedMessage->creationDate = $row['creation_date'];
                $loadedMessage->isRead = $row['is_read'];
                $ret[] = $loadedMessage;
            }
            return $ret;
        }
        return null;
    }

    static public function loadAllMessagesByReceiverId(mysqli $connection, $receiverId) {
        $sql = "SELECT * FROM Messages WHERE receiver_id = $receiverId ORDER BY creation_date DESC";
        $ret = [];
        $result = $connection->query($sql);
        if ($result == true && $result->num_rows != 0) {
            foreach ($result as $row) {
                $loadedMessage = new Message();
                $loadedMessage->id = $row['id'];
                $loadedMessage->senderId = $row['sender_id'];
                $loadedMessage->receiverId = $row['receiver_id'];
                $loadedMessage->text = $row['text'];
                $loadedMessage->creationDate = $row['creation_date'];
                $loadedMessage->isRead = $row['is_read'];
                $ret[] = $loadedMessage;
            }
            return $ret;
        }
        return null;
    }

    static public function addMessage($connection, $time, $text, $senderId, $receiverId) {
        if (1 == 1) {
            $message = new Message();
            $message->setCreationDate($time)->setText($text)->setSenderId($senderId)->setReceiverId($receiverId);

            if ($message->saveToDB($connection) == true) {
                echo "Wysłano wiadomość :)<br>";
                return true;
            } else {
                echo "Błąd, nie udało się wysłać wiadomości :( <br>";
                return false;
            }
        }
    }

}
