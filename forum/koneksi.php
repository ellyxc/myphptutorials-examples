<?php
$username = "root";
$password = "change_me";
$host = "mysql";

return new PDO("mysql:host=$host; dbname=forum", $username, $password);