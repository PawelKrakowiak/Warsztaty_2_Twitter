<?php

class User {

    private $id;
    private $username;
    private $hashedPassword;
    private $email;
    private $gender;
    private $birthDate;

    public function __construct() {
        $this->id = -1;
        $this->username = "";
        $this->email = "";
        $this->hashedPassword = "";
    }

    public function getId() {
        return $this->id;
    }

    public function getEmail() {
        return $this->email;
    }

    public function setEmail($email) {
        $this->email = $email;
        return $this;
    }

    public function getUsername() {
        return $this->username;
    }

    public function setUsername($username) {
        $this->username = $username;
        return $this;
    }

    public function setHashedPassword($password) {

        $this->hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        return $this;
    }

    public function getGender() {
        return $this->gender;
    }

    public function getBirthDate() {
        return $this->birthDate;
    }

    public function setGender($gender) {
        $this->gender = $gender;
        return $this;
    }

    public function setBirthDate($birthDate) {
        $this->birthDate = $birthDate;
        return $this;
    }

    public function checkBirthday() {
        $birthday = date("z", strtotime($this->getBirthDate()));
        $now = date("z");
        if ($birthday - $now >= 0) {
            $timeToBirthday = ($birthday + 1) - ($now + 1);
        } else {
            $timeToBirthday = 365 - ($now + 1) + ($birthday + 1);
        }
        return $timeToBirthday;
    }

    public function saveToDB(mysqli $connection) {
        if ($this->id == -1) {
            $sql = "INSERT INTO Users (email, username, hashed_password, gender, birth_date) VALUES"
                    . "('$this->email', '$this->username', '$this->hashedPassword', '$this->gender', '$this->birthDate')";
            $result = $connection->query($sql);
            if ($result == true) {
                $this->id = $connection->insert_id;
                return true;
            }
        } else {
            $sql = "UPDATE Users SET username = '$this->username', email = '$this->email', hashed_password = '$this->hashedPassword',
                   gender = '$this->gender', birth_date = '$this->birthDate' WHERE id = $this->id";
            $result = $connection->query($sql);
            if ($result == true) {
                return $result;
            }
        }
        return false;
    }

    public function delete(mysqli $connection) {
        if ($this->id != -1) {
            $sql1 = "DELETE FROM Users WHERE id=$this->id";
            $sql2 = "DELETE FROM Tweets WHERE user_id=$this->id";
            $sql3 = "DELETE FROM Comments WHERE user_id=$this->id";
            $sql4 = "DELETE FROM Messages WHERE sender_id=$this->id OR receiver_id=$this->id";
            $result1 = $connection->query($sql1);
            $result2 = $connection->query($sql2);
            $result3 = $connection->query($sql3);
            $result4 = $connection->query($sql4);
            if ($result1 && $result2 && $result3 && $result4) {
                $this->id = -1;
                return true;
            } else {
                var_dump($connection->error_list);
                return false;
            }
        }
        return true;
    }


    static public function logIn(mysqli $connection, $email, $password) {
        $sql = "SELECT * FROM Users WHERE email = '$email'";
        $result = $connection->query($sql);
        if ($result == false) {
            echoCenter("Wystąpił błąd podczas logowania. Spróbuj ponownie.", 1);
            return false;
        } else {
            if ($result->num_rows == 1) {
                $loggingUser = $result->fetch_assoc();
                if (password_verify($password, $loggingUser['hashed_password']) == true) {
                    $_SESSION['loggedUser'] = $loggingUser['id'];
                    $_SESSION['loggedUserName'] = $loggingUser['username'];
                    return true;
                } else {
                    echoCenter("Podane hasło jest nieprawidłowe. Spróbuj ponownie.", 1);
                    return false;
                }
            } else {
                echoCenter("Podany adres email nie występuje w bazie. Spróbuj ponownie lub <a href='register.php'>Zarejestruj się</a>", 1);
                return false;
            }
        }
    }

    static public function logOut() {
        unset($_SESSION['loggedUser']);
                echo "<div class='container text-center'>
                        <div class='row'>
                            <div class='col-md-2'></div>
                            <div class='col-md-8'>
                                <p><h3>Zostałeś poprawnie wylogowany</h3></p>
                            </div>
                            <div class='col-md-2'></div>
                        </div>
                    </div>";
    }

    static public function register(mysqli $connection, $email, $password, $username, $birthDate, $gender) {
        $sql = "SELECT email FROM Users WHERE email = '$email'";
        $emailCheck = $connection->query($sql);
        if ($emailCheck->num_rows == 0) {
            $newUser = new User();
            $newUser->setEmail($email)
                    ->setUsername($username)
                    ->setHashedPassword($password)
                    ->setGender($gender)
                    ->setBirthDate($birthDate);
            if($newUser->saveToDB($connection) == true){
                return true;
            } else{
                return false;
            }   
        } else {
                echo "<div class='container text-center'>
                        <div class='row'>
                            <div class='col-md-2'></div>
                            <div class='col-md-8'>
                                <p><h3 class='fail'>Podany adres e-mail znajdu się już w naszej bazie.</h3></p>
                            </div>
                            <div class='col-md-2'></div>
                        </div>
                    </div>";
            return false;
        }
    }

    static public function loadUserById(mysqli $connection, $id) {
        $sql = "SELECT * FROM Users WHERE id = $id";
        $result = $connection->query($sql);
        if ($result == true && $result->num_rows == 1) {
            $row = $result->fetch_assoc();
            $loadedUser = new User();
            $loadedUser->id = $row['id'];
            $loadedUser->email = $row['email'];
            $loadedUser->username = $row['username'];
            $loadedUser->hashedPassword = $row['hashed_password'];
            $loadedUser->birthDate = $row['birth_date'];
            $loadedUser->gender = $row['gender'];

            return $loadedUser;
        }
        return null;
    }

    static public function loadUserByUsername(mysqli $connection, $username) {
        $sql = "SELECT * FROM Users WHERE username = '$username'";
        $result = $connection->query($sql);
        if ($result == true && $result->num_rows == 1) {
            $row = $result->fetch_assoc();
            $loadedUser = new User();
            $loadedUser->id = $row['id'];
            $loadedUser->email = $row['email'];
            $loadedUser->username = $row['username'];
            $loadedUser->hashedPassword = $row['hashed_password'];
            $loadedUser->birthDate = $row['birth_date'];
            $loadedUser->gender = $row['gender'];

            return $loadedUser;
        }
        return null;
    }

    static public function loadAllUsers(mysqli $connection) {
        $sql = "SELECT * FROM Users";
        $ret = [];
        $result = $connection->query($sql);
        if ($result == true && $result->num_rows != 0) {
            foreach ($result as $row) {
                $loadedUser = new User();
                $loadedUser->id = $row['id'];
                $loadedUser->email = $row['email'];
                $loadedUser->username = $row['username'];
                $loadedUser->hashedPassword = $row['hashed_password'];
                $loadedUser->birthDate = $row['birth_date'];
                $loadedUser->gender = $row['gender'];
                $ret[] = $loadedUser;
            }
        }
        return $ret;
    }

}
