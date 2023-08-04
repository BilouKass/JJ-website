<?php
require_once 'log_db.php';

$PATH_TARGET = '../upload';
$req = $bdd->prepare("SELECT * FROM images");
$req->execute();
$data = $req->fetchAll(PDO::FETCH_ASSOC);
$files = scandir($PATH_TARGET);

for ($i=0; $i < count($files); $i++) { 
    if (!in_array($files[$i], $data)) {
        unlink($PATH_TARGET.'/'.$files[$i]);
    }
    
}