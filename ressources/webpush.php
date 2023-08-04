<?php
use Minishlink\WebPush\WebPush;
use Minishlink\WebPush\Subscription;
function make_notification($title, $url) {
    require_once 'vendor/autoload.php';
    include 'log_db.php';
    $req = $bdd->prepare('SELECT * FROM push');
    $req->execute();
    $user = $req->fetchAll(PDO::FETCH_CLASS);
    $jsonString = file_get_contents("ressources\keys.json");
    $jsonData = json_decode($jsonString, true);
    var_dump($jsonData['privateKey']);
    $webPush = new WebPush([
        'VAPID' => [
            'subject' =>  $jsonData['subject'], // can be a mailto: or your website address
            'publicKey' => $jsonData['publicKey'], // don't forget that your public key also lives in app.js
            'privateKey' => $jsonData['privateKey'], // in the real world, this would be in a secret file
    ]]);
    

    foreach($user as $subscription) {
        $webPush->queueNotification(
            Subscription::create([
                'endpoint' => $subscription->endpoint,
                'publicKey' => $subscription->p256,
                'authToken' => $subscription->auth,
            ]),
            json_encode([
                'message' => $title,
                'title' => 'Nouvelle annonce de l\'ecole jean jaurès',
                'url' => $url
            ])
        );
    }
    foreach ($webPush->flush() as $report) {
        if ($report->isSuccess()) {
        } else {
            if ($report->isSubscriptionExpired()) {
                $end2del = $report->getEndpoint(); #get endpoint to delete from db
                $req = $bdd->prepare('DELETE FROM push WHERE endpoint = :endpoint');
                $req->execute(array('endpoint' => $end2del));
                header("Location: Dashboard.php");
                die(json_encode(['status_code' => 200, 'message' => 'success', 'notif'=>true]));
            }
        }
    }
}