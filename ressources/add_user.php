<?php
require_once "ressources/log_db.php";
require_once "ressources/log.php";

function add_parent ($password, $level, $classe) {
        global $bdd;
        $pass = password_hash($password, PASSWORD_DEFAULT);
        $r = $bdd->prepare("INSERT INTO `parent` (`user`, `password`, `niveau`, `classe`) VALUES (:user, :pass, :level, :classe)");
        $r->execute(array('user'=>$level.'_'.$classe, 'pass'=>$pass, 'level'=>$level, 'classe'=>$classe));
        logging("à créer un parent");
    }
    
function add_prof ($username, $password) {
    global $bdd;

    $pass = password_hash($password, PASSWORD_DEFAULT);
    $r = $bdd->prepare("INSERT INTO `prof` (`nom`, `mdp`) VALUES (:username, :pass)");
    //$r->execute(array('username'=>$username, 'pass'=>$pass));
    logging("à créer un prof");
    $q = $bdd->query('SELECT MAX(Id) FROM prof');
    return $q->fetch();
    }

function edit_prof($username, $password, $classe) {
    global $bdd;
    $pass = password_hash($password, PASSWORD_DEFAULT);
    $req = $bdd->prepare("UPDATE `prof` SET `nom`= :username, `mdp` = :password WHERE classe = :classe");
    $req->execute(array('username'=> $username, 'password'=>$pass, 'classe'=>$classe));
    logging("à modifier un prof");
}
function edit_parent($username, $password, $classe) {
    global $bdd;
    $pass = password_hash($password, PASSWORD_DEFAULT);
    $req = $bdd->prepare("UPDATE `parent` SET `user` = :username, `password` = :password WHERE classe = :classe");
    $req->execute(array('username'=> $username, 'password'=>$pass, 'classe'=>$classe));
    logging("à modifier un parent");
}
