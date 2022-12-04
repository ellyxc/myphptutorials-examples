<?php
$username = "tutorials_user";
$password = "change_me";
$database = "ecommerce";
$host = "postgres";

return new PDO("pgsql:host=$host;dbname=$database;user=$username;password=$password");