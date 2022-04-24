<?php
session_start();
unset($_SESSION['browser']);
unset($_SESSION['ip']);
unset($_SESSION['user']);
session_regenerate_id();
session_destroy();

header("Location: login.php");