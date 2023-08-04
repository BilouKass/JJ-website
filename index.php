<!DOCTYPE html>
<html lang="fr">


<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta name="description" content="Site de">
    <title>#####</title>
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=5.0, user-scalable=5' name='viewport' />
    <link href="assets/css/index/bootstrap.css" rel="stylesheet" />
    <link href="assets/css/index/gaia.css" rel="stylesheet" />
    <link rel="shortcut icon" href="image/favicon.ico" type="image/x-icon">

    <!--     Fonts and icons     -->

    <link href='https://fonts.googleapis.com/css?family=Cambo|Poppins:400,600' rel='stylesheet' type='text/css'>
    <script src="https://kit.fontawesome.com/6836593647.js" crossorigin="anonymous"></script>
    <link href="assets/css/index/fonts/pe-icon-7-stroke.css" rel="stylesheet">
</head>

<body onload="loading();">

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
                    <i class="fa fa-map map"></i> ####
                </a>

            </div>
            <div class="collapse navbar-collapse">
                <ul class="nav navbar-nav navbar-right navbar-uppercase">
                    <li class="dropdown">
                        <a href="#gaia" class="dropdown-toggle" data-toggle="dropdown">
                            <i class="fa fa-share-alt"></i> Infos
                        </a>
                        <ul class="dropdown-menu dropdown-danger">
                            <li>

                                </a>
                            </li>
                            <li>
                                <a href="#">Italien</a>
                            </li>
                            <li>
                                <a href="#">Autres</a>
                            </li>
                        </ul>
                    </li>
                    <li>
                    <a href="login"><i class="fa fa-sign-in enter"></i> Connexions </a>
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
                        <h1 class="title-modern">####</h1>
                        <div class="separator line-separator">♦</div>
                    </div>

                </div>

            </div>
        </div>
    </div>


    <div class="section">
        <div class="container">
            <div class="row">
                <div class="title-area">
                    <h2>Nos Information</h2>
                    <div class="separator separator-danger">✻</div>
                    <p class="description">Retrouvez ci dessous les informations relative à notre école</p>
                </div>
            </div>
            <div class="row">

                <div class="col-md-4">
                    <div class="info-icon">
                        <div class="icon text-danger">
                            <img src="image/carte.webp" alt="icon tel" height="130px" width="130px">
                        </div>
                        <h3>Notre localisation</h3>
                        <iframe title="map" height="300" frameborder="0" scrolling="no" marginheight="0" marginwidth="0"
                            src="https://www.openstreetmap.org/export/embed.html?bbox=5.719411075115205%2C45.189626980899384%2C5.721921622753144%2C45.1908726886507&amp;layer=mapnik&amp;marker=45.190249838183085%2C5.720666348934174"
                            style="border: 1px solid black"></iframe><br /><small><a
                                href="https://www.openstreetmap.org/?mlat=45.19025&amp;mlon=5.72067#map=19/45.19025/5.72067">Afficher
                                une carte plus grande</a></small>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="info-icon">
                        <div class="icon text-danger">
                            <img src="image/info.webp" alt="icon info" height="126px" width="250px">
                        </div>
                        <h3>Information</h3>
                        <div style="overflow: auto; position:relative;border:1px grey solid; height:300px;width:300px; margin:30px;margin-left:auto; margin-right:auto;"
                            class="col">
                            <table style="margin:3px;width:98%;">
                                <tbody id="annonce">
                                    <?php
                                    
                                    require "ressources/article.php";
                                    $alert = new article() ;

                                    $broadcasts = $alert->sort_annonce();
                                    foreach ($broadcasts as $broad) {?>
                                    <tr style="min-height: 48px;border: 1px black solid;">
                                        <th class="art_container">
                                        <a href="lecture?id=<?=$broad['id_article']?>" class="broadcast"><?= $broad['titre']?></a>
                                        </th>
                                    </tr>
                                    <?php }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                        <button onclick="ask_notifications()" class="btn btn-primary btn-xs btn-primary"
                            id="button_notifications" style="margin: 20px;">me notifier lors de nouvelles
                            annonces</button>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="info-icon">
                        <div class="icon text-danger">
                            <img src="image/tel.png" alt="icon tel" width="130px" height="130px">
                        </div>
                        <h3>Nous contacter</h3>
                        <p class="description" style="margin-top: 80px; color:black">Direction : 06 01 02 03 04</p>
                        <p class="description" style="margin-top: 80px; color:black">Périscolaire: 06 01 02 03 05</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="footer footer-big footer-color-black" data-color="black">
        <div class="container">
            <div class="copyright">
                <p>© <?= date('Y') ?> Créer par creative tim et adapté par Baptiste Cassou</p>
            </div>
            <div class="legal">
                <a href="legal.html">Mentions légals</a>
            </div>
        </div>
    </footer>

</body>

<script>
const button = document.getElementById('button_notifications');

function loading() {
    if (!('Notification' in window)) {
        button.style.display = 'none';
    } else if (Notification.permission === 'granted') {
        button.style.display = 'none';
    } else if (Notification.permission === 'denied') {
        button.innerHTML = 'Vous avez refusé les notifications'
    }
}

function ask_notifications() {
    console.log(Notification.permission);
    if (Notification.permission !== 'denied') {
        Notification.requestPermission().then(function() {

            if (Notification.permission === 'granted') {
                button.innerHTML = 'Notification acceptées';
                button.hidden = true;
                registerServiceWorker();
            } else if (Notification.permission === 'denied') {
                button.innerHTML = 'Notification refusées';
                button.disable = true;
            }
        })
    }
}
</script>

<script src="assets/js/jquery.min.js" type="text/javascript"></script>
<script src="assets/js/bootstrap.js" type="text/javascript"></script>

<!--  js library for devices recognition -->
<script type="text/javascript" src="assets/js/modernizr.js"></script>

<!--   file where we handle all the script from the Gaia - Bootstrap Template   -->
<script type="text/javascript" src="assets/js/gaia.js"></script>
<script src="assets/js/notif.js" type="text/javascript"></script>
</html>
<!--

=========================================================
* Gaia Bootstrap Template - v1.0.1
=========================================================

* Product Page: https://www.creative-tim.com/product/gaia-bootstrap-template
* Licensed under MIT (https://github.com/creativetimofficial/gaia-bootstrap-template/blob/master/LICENSE.md)
* Copyright 2019 Creative Tim (http://www.creative-tim.com)

* Coded by Creative Tim

=========================================================

* The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

-->