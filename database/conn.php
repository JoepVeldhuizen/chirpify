<?php

$username = "root";
$password = '';



try {
    $conn = new PDO("mysql:host=localhost;dbname=chirpify", $username, $password);
}catch (PDOException $e){
    echo $e->getMessage();
}