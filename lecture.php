<?php
session_start();

if (is_numeric($_GET['id'])) {
    require 'ressources/article.php';
    require 'ressources/log.php';
    $id_article = $_GET['id'];
    $bdd1 = new article();
    if (isset($_POST['type']) and isset($_SESSION['id_prof'])) {
        if ($_POST['type'] === 'Supprimer'){
            var_dump($_POST);
            echo $id_article;
            var_dump($_GET);
            $bdd1->delete($id_article);
            logging("delete an article (id= $id_article)");
            header('Location: dashboard');
            die();
        }
        else {
            header('Location: edition');
            die();
        }
    }
    $article = $bdd1->lire_article($id_article);
    if (!empty($article)) {
        if ($article[0]['type'] === 'annonce' or (isset($_GET['id']) and isset($_SESSION['username']))) {
            $images = array();
            foreach ($article as $art) {
                $break_str = explode('.', $art['image_name']);
                $extension = strtolower(end($break_str));
                if (in_array($extension, array('pdf', 'docx', 'txt', 'doc'))) {
                    $type = 'doc';
                }
                elseif (in_array($extension, array('png', 'jpeg', 'jpg', 'gif'))) {
                    $type = 'img';
                }
                else {
                    $type = 'none';
                }
                array_push($images, array('path'=>$art['path'], 'name'=>$art['image_name'], 'type' => $type));
            }
            $article = $article[0];
            ?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/carroussel.css">
    <script src="https://kit.fontawesome.com/6836593647.js" crossorigin="anonymous"></script>
    <title>Article de <?=$article['nom']?></title>
    <!--<link rel="stylesheet" href="assets/css/reset.min.css" />-->
    <link rel="stylesheet" href="assets/css/lecture.css">
    <link rel="stylesheet" href="assets/css/popup.css">
</head>

<body id="start">
    <?php

    if (isset($_SESSION['id_prof'])) { ?>
    <div id="popup">
        <div class="container2">
            <h2 class="popup-text">Etes vous sur ?</h2>
            <div class="buttons">
                <input type="button" id="popup_cancel" value="Annuler">
                <form action="" method="post">
                    <input type="submit" id="popup_delete" value="Supprimer">
                </form>
            </div>
        </div>
    </div> <?php }
    require "ressources/navbar.php"; ?>
    <div class="headr">
        <h1 class="title"><strong><?=$article['titre']?></strong></h1>
        <p class="sub">écrit par <?=$article['nom']?> le <?=$article['date']?></p>
        <p class="content"><?=nl2br($article['article_content'])?></p>
    </div>
    <div class="slideshow-container">
        <?php
    $doc = array();
    if (!is_null($images) and !is_null($images[0]['path'])) {
        $i = 0;
        $total = count($images);
    foreach ($images as $image) {
        $i++;
        if ($image['type'] === 'img') {
        ?>

        <!-- Full-width images with number and caption text -->
        <div class="mySlides fade" style="text-align:center">
            <div class="numbertext"><?=$i?> / <?=$total?></div>
            <img src="<?=$image['path']?>" style="max-width: 100%; max-height:600px;" alt="images">
            <div class="text">
                <?=$image['name']?>
            </div>
        </div>
        <?php }} 
        $count_img = array_filter($images, function ($image) {
            return $image['type'] === 'img';
        });
        if (count($count_img) > 1) { ?>
        <!-- Next and previous buttons -->
        <a class="prev" onclick="plusSlides(-1)">&#10094;</a>
        <a class="next" onclick="plusSlides(1)">&#10095;</a> <?php } ?>
    </div>
    <br>

    <!-- The dots/circles -->
    <div style="text-align: center;">
        <div class="dot">
            <?php
        $c = 1;
        foreach ($images as $_image) {
            if ($_image['type'] === 'img') { ?>
            <a class="page" onclick="currentSlide(<?=$c?>)"><?=$c?></a>
            <?php 
                $c++; }} 
                ?>
        </div><?php } ?>
    </div>
    <div class="download-button">
        <?php
        foreach ($images as $image) {
            if ($image['type'] === 'doc') { ?>
        <a href=<?=$image['path']?> target="_blank" class="btn"><i class="fa fa-download"></i> Télécharger
            <?=$image['name']?></a>
        <?php }} ?>
    </div>
    <?php
    if (isset($_SESSION['username'])) {
        if ($_SESSION['username'] === 'root') { ?>
    <div class="buttons_admin">
        <form action="<?= $_SERVER['REQUEST_URI'] ?>" method="post">
            <input class="del_but" type="button" name="type" value="Supprimer">
        </form>
        <form action="edition" method="post">
            <input type="hidden" style=" display:none; position:absolute;" value="<?=$id_article?>" name="modifier">
            <input class="modif_but" type="submit" value="modifier">
        </form>
    </div>
    <?php }}
    ?>
</body>
<script src="assets/js/carroussel.js"></script>
<script src="assets/js/del_art.js"></script>

</html>

<?php 
    $bdd1->add_viewer($id_article);
    }
    else {
        header('Location: index');
        die();
    }
}
else {
    header('Location: articles#art');
    die();
}
}
else {
    header('Location: articles#art'); 
    die();}
    
?>