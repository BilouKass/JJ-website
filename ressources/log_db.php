<?php
$jsonString = file_get_contents("ressources\keys.json");
$jsonData = json_decode($jsonString, true);
$bdd = new PDO('mysql:host=localhost;dbname=school.db;charset=utf8', $jsonData['DB'], $jsonData['DB-password']);


?>