<?php
    session_start();
    $_SESSION = [];
    session_destroy();
    header("Location: /palestra/index.php");
    exit();
?>