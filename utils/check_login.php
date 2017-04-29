<?php
if (! isset($_SESSION['loggedUser'])) {
    header("location: login.php");
}
