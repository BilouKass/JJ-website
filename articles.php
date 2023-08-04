<?php 
require 'ressources/article.php';
require 'ressources/classes_lib.php';
session_start();
$Classe = new Classe();
function return_menu() {
    session_destroy();
    header('Location: index');
    die();
}

if (isset($_SESSION['username'])) {

$cls_article = new article();
if (isset($_SESSION['id_parent'])) {
}
else {
    return_menu();
}
$prof = $Classe->get_id_prof_from_parent($_SESSION['id_parent']);
$content = $cls_article->get_and_sort_article($prof[0]['professeur']);
$counter = count($content);
if ($counter > 2) {
    $columns = 3;
 }
else {
    $columns = $counter;
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8" />

    <meta name="description" content="Site de l'ecole jean Jaures">
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=5.0, user-scalable=5' name='viewport' />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <title>Ecole Jean Jaures</title>
    <link href="assets/css/index/bootstrap.css" rel="stylesheet" />
    <link href="assets/css/index/gaia.css" rel="stylesheet" />
    <link rel="stylesheet" href="assets/css/article.css">
    <link href='https://fonts.googleapis.com/css?family=Cambo|Poppins:400,600' rel='stylesheet' type='text/css'>
    <script src="https://kit.fontawesome.com/6836593647.js" crossorigin="anonymous"></script>
    <link href="assets/css/index/fonts/pe-icon-7-stroke.css" rel="stylesheet">

</head>

<body>

<nav class="navbar navbar-default navbar-transparent navbar-fixed-top" color-on-scroll="200">
        <!-- if you want to keep the navbar hidden you can add this class to the navbar "navbar-burger"-->
        <div class="container">
            <div class="navbar-header">
                <button id="menu-toggle" type="button" class="navbar-toggle" data-toggle="collapse"
                    data-target="#example">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar bar1"></span>
                    <span class="icon-bar bar2"></span>
                    <span class="icon-bar bar3"></span>
                </button>
                <a href="https://goo.gl/maps/mo5YhQsqt8R5GWfk7" class="navbar-brand">
                    <i class="fa fa-map map"></i> 9 Cours Jean Jaures
                </a>

            </div>
            <div class="collapse navbar-collapse">
                <ul class="nav navbar-nav navbar-right navbar-uppercase">
                    <li class="dropdown">
                        <a href="#gaia" class="dropdown-toggle" data-toggle="dropdown">
                            <i class="fa fa-share-alt"></i> Navigation
                        </a>
                        <ul class="dropdown-menu dropdown-danger">
                            </li>
                            <?php 
                            
                            ?>
                            <li>
                                <a href="#">Autres</a>
                            </li>
                        </ul>
                    </li>
                    <li>
                    <li>
                        <a href="ressources/disconnect.php"><i class="fa fa-sign-out enter"></i> Déconnexion</a>
                    </li>
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
    </nav>

    <div class="section section-header">
        <div class="parallax filter filter-color-red">
            <div class="image" style="background-image: url('image/ecole.webp');">
            </div>
            <div class="container">
                <div class="content">
                    <div class="title-area">
                        <h1 class="title-modern">Ecole Jean Jaures</h1>
                        <div class="separator line-separator">♦</div>
                        <h2 class="title-modern">Page d'article</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
        <h2 style="text-align:center ;">liste des articles</h2>
    <!--<div class="container"> -->
        <div class="arts_buttons" id="art" style="grid-template-columns:<?= str_repeat("1fr ", $columns)?> ;">

                <?php
                    if ($counter > 0) {
                    $count = 1;
                    foreach ($content as $one_art) {?>
                        <div class="art_button">
                            <a href="lecture?id=<?=$one_art['id_article']?>" class="but_art"><?="<span class='title'>{$one_art['titre']}</span><br>
                                <span class='author'>{$one_art['nom']}</span><br>
                                <span class=date>" .  str_replace(array(' ', ':'), array('<br>', 'h'),  substr($one_art['date'], 0, -3)) ." </span>"?>
                            </a>
                        </div> <?php
                    
                    if ($count === 5) {
                        $count = 0;
                        echo "</tr><tr>";
                    } 
                    $count++; }}
                    else { 
                        echo "Il n'y a pas encore d'article pour ce niveau";
                    }?>
        </div>
    <!--</div>-->
    
    <footer class="footer footer-big footer-color-black" data-color="black">
        <div class="container">
            <div class="copyright">
                <p>© <?= date('Y') ?> Créer par creative tim et adapté par Baptiste Cassou</p>
            </div>
            <div class="legal">
                <a href="legal">Mentions légals</a>
            </div>
        </div>
    </footer>
</body>
<script src="assets/js/jquery.min.js" type="text/javascript"></script>
<script src="assets/js/bootstrap.js" type="text/javascript"></script>

<!--  js library for devices recognition -->
<script type="text/javascript" src="assets/js/modernizr.js"></script>

<!--   file where we handle all the script from the Gaia - Bootstrap Template   -->
<script type="text/javascript" src="assets/js/gaia.js"></script>
<script>
    window.scrollTo(50,50)
</script>
</html>
<?php }
else {
    return_menu();
}