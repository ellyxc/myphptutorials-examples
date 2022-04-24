<?php

$host = "mysql";
$user = "root";
$password = "change_me";
$database = "kuis";

return new PDO("mysql:host=$host;dbname=$database", $user, $password, array(
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
));
