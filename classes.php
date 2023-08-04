<?php
session_start();
function reject()
{
    logging("A ESSAYER DE SE CONNECTER A LA PAGE SUPERADMIN");
    header('Location: 404.html');
    die();
}
include 'ressources/log.php';
require "ressources/log_db.php";
require "ressources/classes_lib.php";
$Classe = new Classe();
if (isset($_SESSION)) {
    if ($_SESSION !== []) {
        if ($_SESSION['username'] === 'root') {
            if (isset($_POST)) {
                if ($_POST !== []) {
                    $data = $_POST;
                    $id_button = array_keys($data)[0];
                    if ($data[$id_button] === 'Supprimer') {
                        $Classe->delete_classe($id_button);
                    } elseif ($data['type'] === 'Valider') {
                        $state = $Classe->edit_classe($data['id'], $data['prof_username'], $data['prof_password'], $data['parent_username'], $data['parent_password']);
                        unset($_POST);
                        if ($state === 400) {
                            echo "<script>alert('une erreur est survenu')</script>";
                        }
                    } elseif ($data['type'] === 'ajouter') {

                        $Classe->add_new_classe($data['prof_username'], $data['prof_password'], $data['parent_username'], $data['parent_password'], $data['classe']);
                        unset($_POST);
                        header('Location: classes');
                        die();
                    }
                }
            } ?>
            <!DOCTYPE html>
            <html lang="fr">

            <head>
                <meta charset="UTF-8">
                <meta http-equiv="X-UA-Compatible" content="IE=edge">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <link rel="stylesheet" href="assets/css/classes_style.css">
                <title>Listes des classes</title>
            </head>

            <body id="start">

                <div id="popup">
                    <div class="container2">
                        <h2 class="popup-text">Etes vous sur ?</h2>
                        <div class="buttons">
                            <input type="button" id="popup_cancel" value="Annuler">
                            <form action="classes" method="post">
                                <input type="submit" id="popup_delete" value="Supprimer">
                            </form>
                        </div>
                    </div>
                </div>
                <div id="form-new">
                    <form action="" method="post">
                        <input type="text" name="prof_username" class="prof_username input">
                        <input type="password" name="prof_password" class="prof_password input">
                        <input type="text" name="parent_username" class="parent_username input">
                        <input type="password" name="parent_password" class="parent_password input">
                        <select name="classe" id="select-classe">
                            <option value="CP">CP</option>
                            <option value="CE1">CE1</option>
                            <option value="CE2">CE2</option>
                            <option value="CM1">CM1</option>
                            <option value="CM2">CM2</option>
                        </select>
                        <input type="submit" name="type" value="ajouter">
                        <button onclick="stop_new()">Annuler</button>
                    </form>
                </div>
                <?php
                require 'ressources/navbar.php'; ?>
                <div id="main-container">
                    <?php
                    $c = 0;

                    $classes = $Classe->get_prof_and_parent(null);
                    foreach ($classes as $key => $classe) { ?>
                        <div class="container-solid">
                            <form action="" method="post" class="classe-container">
                                <div class="inputs">
                                    <input type="hidden" name="id" value="<?=$classe['id_classe']?>">
                                    <input type="text" name="prof_username" class="prof-username input" value="<?= $classe['nom'] ?>" readonly>
                                    <input type="password" name="prof_password" class="prof-password input" placeholder="Mot de passe prof" style="display:none;">
                                    <input type="text" name="parent_username" class="parent-username input" value="<?= $classe['user'] ?>" readonly>
                                    <input type="password" name="parent_password" class="parent-password input" placeholder="Mot de passe parent" style="display: none;">

                                </div>
                                <div class="buttons">
                                    <button type="button" class="modif_but" id="button_modif_<?= $c ?>"> <!--event listener pour input -->
                                        Modifier
                                    </button>
                                    <button type="button" class="del_but" name="<?= $classe['id_classe'] ?>">
                                        Supprimer
                                    </button>
                                    <input type="submit" value="Valider" name="type" class="button_valid">
                                </div>
                            </form>

                        </div>
                    <?php
                        $c++;
                    }

                    ?>
            </div>
            <div class="add_classe">
                <button id="new_classe">
                <i class="fa fa-add"></i>
                        nouvelle classe
                    </button>
            </div>
            </body>
            <script src="assets/js/classes.js"></script>
            <script src="assets/js/del_art.js"></script>

            </html>

<?php
        } else {
            reject();
        }
    } else {
        reject();
    }
} else {
    reject();
}
