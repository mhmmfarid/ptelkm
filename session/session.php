<?php
function validateSession($requiredStatus) {
    session_start();

    if (!isset($_SESSION['user_id']) || $_SESSION['status'] != $requiredStatus) {
        header("Location: ../login/login.php");
        exit();
    }
}
?>
