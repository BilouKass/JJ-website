<?php
require "ressources/log_db.php";
$admin = false;
$endpoint = "/school";
$loc = $_SERVER['PHP_SELF'];
if (isset($_SESSION['username'])) {
  $user = $_SESSION['username'];
  if ($user === 'root') {
    $type = 'superadmin';
  }
  elseif ($_SESSION['type'] === 'prof') {
    $type = 'admin';
  }}
  else {
    $type = 'normal';
  }
if ($loc === "$endpoint/dashboard.php" and isset($user)) {
    $title = "Page d'administration"; 
    $target = 'creation.php';
    $label = 'Nouvelle publication';
    if ($user === 'root') {
      $label = 'Faire une annonce'; }
    $admin = true;
    $icon = 'pencil';
  }
  elseif ($loc === "$endpoint/creation.php" or $loc === "$endpoint/edition.php" and isset($user)) {
    $title = "Page d'administration"; 
    $target = 'dashboard.php';
    $label = 'Menu principal';
    $broad = '';
    $icon = 'xmark';
    $admin = true;
  }
  elseif ($loc === "$endpoint/admin_users.php" or $loc == "$endpoint/classes.php") {
    $label = 'Retour';
    $target = 'dashboard';
    $icon = 'home';
  }

  elseif ($loc === '/school/lecture.php') {
    $title = 'Menu principal';
    if (isset($type)) {
      if ($type === 'admin' or $type === 'superadmin') {
      $target = 'dashboard.php';
      $label = 'Retour';
      $icon = 'home';
    }
    elseif ($type === 2) {
      $target = 'index.php';
      $label = 'Retour';
      $icon = 'home';
    }

    else{
      $target = 'articles.php#art';
      $label = 'Menu des articles';
      $icon = 'home';
    }}}
?> 
<!-- alexandria-->
<link rel="stylesheet" href="assets/css/navbar.css">
<script src="https://kit.fontawesome.com/6836593647.js" crossorigin="anonymous"></script>

<nav class="navbar navbar-default navbar-static-top" style="background-color: #ffa3a9;">
  <div class="container">
    <div class="navbar-header">

      <a class="navbar-brand" href="index.php" style="color:black; margin:6px"> Titre</a>
    </div>
    <ul class="nav navbar-nav navbar-right">
      <li class="active"><a href="<?=$target?>" class="nav-buttons2"><i class="fa fa-<?=$icon?>"></i> <?=$label?></a></li>
      <?php
      if (isset($user) and $loc === '/school/dashboard.php') {
        if ($user  === 'root') {?>
              <li><a href="classes.php" class="nav-buttons2"><i class="fa fa-gear"></i> Gérer les classes</a></li>
      <?php }}
      if ($admin === true) { ?>
        <li><a href="ressources/disconnect.php" class="nav-buttons2"><i class="fa fa-sign-out"></i> Deconnection</a></li> <?php } ?>


    </ul>
  </div>
</nav>
