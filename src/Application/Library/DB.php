<?php
function connect_db() {
    $server = 'localhost';
    $user = 'db_user';
    $pass = 'db_pass';
    $database = 'db_name';
    $connection = new mysqli($server, $user, $pass, $database);
    return $connection;
}