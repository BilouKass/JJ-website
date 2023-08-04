<?php
require "log_db.php";

$contentType = isset($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';
$content = json_decode(trim(file_get_contents("php://input")), true);
if ($contentType !== 'application/json') {
    die(json_encode(
        array(
            'status' => '400',
            'message' => 'JSON content is not a valid JSON'
        )
    ));
}

$req = $bdd->prepare("INSERT INTO `push`(`endpoint`, `auth`, `p256`) VALUES (:endpoint, :auth, :p256)");
$req->execute(array('endpoint' => $content['endpoint'], 'auth' => $content['keys']['auth'], 'p256' => $content['keys']['p256dh']));
die(json_encode(
    array(
            'status' => '200',
            'message' => 'LGTM',
            'content' => array('endpoint' => $content['endpoint'], 'auth' => $content['keys']['auth'], 'p256' => $content['keys']['p256dh'])
        )
        ));
    
    