<?php
// Version: 1.0
function str_start_with( $haystack, $needle ) {
    return strpos( $haystack , $needle ) === 0;
}
function array_sort_by_column(&$arr, $col, $dir = SORT_DESC) {
    $sort_col = array();
    if (str_start_with('r', $col)) {
        $dir = SORT_ASC;
        substr($col, 1);
    }
    if (!array_key_exists($col, $arr[0])) {
        $col = 'date';
    }
    foreach ($arr as $key => $row) {
        $sort_col[$key] = $row[$col];

    }
    return array_multisort($sort_col, $dir, $arr);
}
?>

<?php
session_start();
if (!isset($_GET['sort'])) {
    $sort_target = 'date';
}
else {
    if (is_string($_GET['sort'])) {
        $sort_target = $_GET['sort'];
    }
}
if (isset($_SESSION['username']) and isset($_SESSION['id_prof'])){
    require "ressources/article.php";
    include "ressources/log.php";
    $articles = new article();
    if (isset($_POST) and !empty($_POST)) {
        $id_button = array_keys($_POST)[0];
        if ($_POST[$id_button] === 'Supprimer' and is_int($id_button)) {
            $articles->delete($id_button);
            unset($_POST[$id_button]);
            logging("Delete an article (id= $id_button)");
        }
        else {
            logging("Args errors for del");
        }
    }

    ?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/reset.min.css" />
    <link rel="stylesheet" href="assets/css/dashboard.css">
    <link rel="stylesheet" href="assets/css/popup.css">
    <title>Dashboard</title>
</head>

<body id="start">

    <?php
    

    if (isset($_SESSION['id_prof'])) {
    $content = $articles->lire_root($_SESSION['id_prof']);}
    else {
        echo "<br>erreur de chargement des articles";
        $content = array();
    }
        ?>
    <div id="popup">
        <div class="container2">
            <h2 class="popup-text">Etes vous sur ?</h2>
            <div class="buttons">
                <input type="button" id="popup_cancel" value="Annuler">
                <form action="dashboard.php" method="post">
                    <input type="submit" id="popup_delete" value="Supprimer">
                </form>
            </div>
        </div>
    </div>
    <?php
        require "ressources/navbar.php";
    ?>
    <div style="text-align: center; ">
        <h3>VOS ARTICLES (<?= count($content)?>)</h3>
        <table>
            <?php

            if (count($content) >= 1) {
            array_sort_by_column($content, $sort_target);
            foreach ($content as $one_art) { ?>
            <tr>
                <td class="arti">
                    <span class="title"><?=$one_art['titre'] . " "?> </span>
                    <p> <?=$one_art['views']?> vues</p>
                    <p class="post"><?='Posté le: ' . $one_art['date']?></p>
                    <p class="auteur">par: <?=$one_art['nom']?></p>

                    <div class="buttons">
                        <input class="del_but" type="button" value="supprimer" name="<?=$one_art['id_article']?>">
                        <form action="edition" method="post">
                            <input type="text" style="visibility:hidden; position:absolute;" value="<?=$one_art['id_article']?>" name="modifier">
                            <input class="modif_but" type="submit" value="modifier">
                        </form>
                        <form action="lecture" method="get">
                            <input type="text" style="visibility:hidden; position:absolute;" value="<?=$one_art['id_article']?>" name="id">
                            <input type="submit" value="Voir" class="show_but">
                        </form>
                    </div>
                </td>
            </tr>
            <?php }}
                else echo "<br>Vous n'avez pas encore d'article" ?>
        </table>
    </div>
</body>
<script src="assets/js/del_art.js"></script>
</html>
<?php }
else {
    header("Location: index");
    die();
} ?>