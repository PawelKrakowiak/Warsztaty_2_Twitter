<?php

class Tweet {

    private $id;
    private $userId;
    private $text;
    private $creationDate;

    public function __construct() {
        $this->id = -1;
        $this->userId = "";
        $this->text = "";
        $this->creationDate = "";
    }

    public function getId() {
        return $this->id;
    }

    public function getUserId() {
        return $this->userId;
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
            $sql = "INSERT INTO Tweets (user_id, text, creation_date) VALUES"
                    . "('$this->userId', '$this->text', '$this->creationDate')";
            $result = $connection->query($sql);
            if ($result == true) {
                $this->id = $connection->insert_id;
                return true;
            }
        } else {
            $sql = "UPDATE Tweets SET user_id = '$this->userId', text = '$this->text', creation_date = '$this->creationDate'
                   WHERE id = $this->id";
            $result = $connection->query($sql);
            if ($result == true) {
                return $result;
            }
        }
        return false;
    }

    static public function loadTweetById(mysqli $connection, $id) {
        $sql = "SELECT * FROM Tweets WHERE id = $id";
        $result = $connection->query($sql);
        if ($result == true && $result->num_rows == 1) {
            $row = $result->fetch_assoc();
            $loadedTweet = new Tweet();
            $loadedTweet->id = $row['id'];
            $loadedTweet->userId = $row['user_id'];
            $loadedTweet->text = $row['text'];
            $loadedTweet->creationDate = $row['creation_date'];

            return $loadedTweet;
        }
        return null;
    }

    static public function loadAllTweetsByUserId(mysqli $connection, $userId) {
        $sql = "SELECT * FROM Tweets WHERE user_id = $userId ORDER BY creation_date DESC";
        $ret = [];
        $result = $connection->query($sql);
        if ($result == true && $result->num_rows != 0) {
            foreach ($result as $row) {
                $loadedTweet = new Tweet();
                $loadedTweet->id = $row['id'];
                $loadedTweet->userId = $row['user_id'];
                $loadedTweet->text = $row['text'];
                $loadedTweet->creationDate = $row['creation_date'];
                $ret[] = $loadedTweet;
            }
        }
        return $ret;
    }

    static public function loadAllTweets(mysqli $connection) {
        $sql = "SELECT * FROM Tweets ORDER BY creation_date DESC";
        $ret = [];
        $result = $connection->query($sql);
        if ($result == true && $result->num_rows != 0) {
            foreach ($result as $row) {
                $loadedTweet = new Tweet();
                $loadedTweet->id = $row['id'];
                $loadedTweet->userId = $row['user_id'];
                $loadedTweet->text = $row['text'];
                $loadedTweet->creationDate = $row['creation_date'];
                $ret[] = $loadedTweet;
            }
        }
        return $ret;
    }

    static public function addTweet($connection, $time, $text, $userId) {
        if (1 == 1) {
            $tweet = new Tweet();
            $tweet->setCreationDate($time)->setText($text)->setUserId($userId);
            if ($tweet->saveToDB($connection) == true) {
                echo "Dodano nowego Tweeta :)<br>";
                return true;
            } else {
                echo "Błąd, nie udało się dodać Tweeta :( <br>";
                return false;
            }
        }
    }

}
