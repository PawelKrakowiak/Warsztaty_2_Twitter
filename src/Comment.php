<?php

class Comment {

    private $id;
    private $userId;
    private $tweetId;
    private $text;
    private $creationDate;

    public function __construct() {
        $this->id = -1;
        $this->userId = "";
        $this->tweetId = "";
        $this->text = "";
        $this->creationDate = "";
    }

    public function getId() {
        return $this->id;
    }

    public function getUserId() {
        return $this->userId;
    }

    public function getTweetId() {
        return $this->tweetId;
    }

    public function getText() {
        return $this->text;
    }

    public function getCreationDate() {
        return $this->creationDate;
    }

    public function setUserId($userId) {
        $this->userId = $userId;
        return $this;
    }

    public function setTweetId($tweetId) {
        $this->tweetId = $tweetId;
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

    public function saveToDB(mysqli $connection) {
        if ($this->id == -1) {
            $sql = "INSERT INTO Comments (tweet_id, user_id, text, creation_date) VALUES"
                    . "('$this->tweetId', '$this->userId', '$this->text', '$this->creationDate')";
            $result = $connection->query($sql);
            if ($result == true) {
                $this->id = $connection->insert_id;
                return $result;
            } else{
                echo $connection->error;
            }
        } else {
            $sql = "UPDATE Comments SET tweet_id = '$this->tweetId', user_id = '$this->userId', text = '$this->text', creation_date = '$this->creationDate'
                   WHERE id = $this->id";
            $result = $connection->query($sql);
            if ($result == true) {
                return $result;
            }
        }
        return $result;
    }

    static public function loadCommentById(mysqli $connection, $id) {
        $sql = "SELECT * FROM Comments WHERE id = $id";
        $result = $connection->query($sql);
        if ($result == true && $result->num_rows == 1) {
            $row = $result->fetch_assoc();
            $loadedComment = new Comment();
            $loadedComment->id = $row['id'];
            $loadedComment->tweetId = $row['tweet_id'];
            $loadedComment->userId = $row['user_id'];
            $loadedComment->text = $row['text'];
            $loadedComment->creationDate = $row['creation_date'];

            return $loadedComment;
        }
        return null;
    }

    static public function loadAllCommentsByTweetId(mysqli $connection, $tweetId) {
        $sql = "SELECT * FROM Comments WHERE tweet_id = $tweetId ORDER BY creation_date DESC";
        $ret = [];
        $result = $connection->query($sql);
        if ($result == true && $result->num_rows != 0) {
            foreach ($result as $row) {
                $loadedComment = new Comment();
                $loadedComment->id = $row['id'];
                $loadedComment->tweetId = $row['tweet_id'];
                $loadedComment->userId = $row['user_id'];
                $loadedComment->text = $row['text'];
                $loadedComment->creationDate = $row['creation_date'];
                $ret[] = $loadedComment;
            }
            return $ret;
        }
        return null;
    }

    static public function addComment($connection, $time, $text, $userId, $tweetId) {
        if (1 == 1) {
            $comment = new Comment();
            $comment->setCreationDate($time)->setText($text)->setUserId($userId)->setTweetId($tweetId);
            
            if ($comment->saveToDB($connection) == true) {
                echo "Dodano nowy komentarz :)<br>";
                return true;
            } else {
                echo "Błąd, nie udało się dodać komentarza :( <br>";
                return false;
            }
        }
    }
}
