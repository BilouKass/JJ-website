<?php
require 'ressources/log_db.php';
include 'ressources/log.php';

session_start();
if (!isset($_SESSION['username'])) {
if (isset($_POST['password']) and isset($_POST['identifiant'])) { #si la variable mot de passe existe
    $path = 'antibrute/'.$_POST['identifiant'].'.tmp';
    if(file_exists($path)) {
        $data_file = stat($path);
        $try_file = fopen($path, 'r+');
        $try = fgets($try_file);
        $last_t = $data_file['mtime'];
        if(date('d/m/Y', $last_t) == date('d/m/Y')) {
            $action = 0;
        }
        else {
            $action = 2;
        }
    }
    else { // not found
        //echo "file not found:";
        $action = 1;
        $try = 0;
    }

    $mdp = $_POST['password'];
    $login = $_POST['identifiant'];
    // prof
    $r_prof = $bdd->prepare("SELECT id_prof, mdp FROM prof WHERE nom = :username");
	$r_prof->execute(array('username'=>$login));
    $check_prof = $r_prof->fetchAll(\PDO::FETCH_ASSOC);

    if (count($check_prof) === 1 and $try < 10) {
        echo password_verify($mdp, $check_prof[0]['mdp']);
        if (password_verify($mdp, $check_prof[0]['mdp'])) {
        
            $_SESSION['username'] = $login;
            $_SESSION['type'] = 'prof';
            $_SESSION['id_prof'] = $check_prof[0]['id_prof'];
            if (isset($_SESSION['id_prof']) and isset($_SESSION['username'])) {
                logging("Login");
                header("Location: dashboard.php");
                die();}
    }}
    //

    else { // parents
        $r_parents = $bdd->prepare('SELECT id_parent,user, password FROM parent WHERE user = :username');
        $r_parents->execute(array('username'=>$login));
        $check_parents = $r_parents->fetchAll(\PDO::FETCH_ASSOC);
        if (count($check_parents) === 1 and $try < 10) {
            if (password_verify($mdp, $check_parents[0]['password'])) {
                $_SESSION['username'] = $login;
                $_SESSION['type'] = 'parent';
                $_SESSION['id_parent'] = $check_parents[0]['id_parent'];
                logging("login");
                header('Location: articles.php#art');
                die();
                }
            }
    }    
    if ($action===2) {//mauvaise date
        file_put_contents($path, '1'); // On met à jour le contenu du fichier
    }

    elseif ($action===1) { //creation fichier
        file_put_contents('antibrute/'.$_POST['identifiant'].'.tmp', '1');
    }

    elseif ($action===0) { //error // On place le curseur juste devant le nombre de tentatives
        $try += 1;
        file_put_contents($path,  strval($try)); // On ajoute 1 au nombre de tentatives
    }
}?>
<!doctype html>
<html lang="fr">

<head>
    <title>Page de connection</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">


    <link rel="stylesheet" href="assets/css/style.css">

</head>

<body>
    <section class="ftco-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6 text-center mb-5">
                    <h2 class="heading-section">Connection</h2>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-md-7 col-lg-5">
                    <div class="login-wrap p-4 p-md-5">
                        <div class="icon d-flex align-items-center justify-content-center">
                            <span class="fa fa-user-o"></span>
                        </div>
                        <h3 class="text-center mb-4">se connecter</h3>
                        <?php if (isset($try)) {
                                        $try = intval($try);
                                        $try = 10-$try;
                                        if ($try <= 3 and $try >= 0) {
                                            echo "$try essais restants";}
                                        elseif ($try < 0) {
                                            echo "<script>alert('Connection bloquée à cause de trop nombreuses tentatives erronés, réessayez demain')</script>";
                                        }
                                        } ?>
                        <form action="login.php" class="login-form" method="POST">
                            <div class="form-group">
                                <input type="text" class="form-control rounded-left" placeholder="nom d'utilisateur"
                                    name="identifiant" required>
                            </div>
                            <div class="form-group d-flex">
                                <input type="password" class="form-control rounded-left" placeholder="Mot de passe"
                                    name="password" required>
                            </div>
                            <div class="form-group">
                                <button type="submit"
                                    class="form-control btn btn-primary rounded submit px-3"><span class="btn-connection">Connection </span></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</body>

<?php
if ($action === 0) {?>
    <script>
    document.getElementById('status').innerHTML = "mot de passe incorect";

    setTimeout(function() {
        document.getElementById('status').innerHTML = "";
    }, 4000);
    </script>
<?php }}
else {
    if ($_SESSION['type'] === 'prof') {
        header('location: dashboard.php');
        die();
    }
    else {
        header('location:articles.php');
        die();
    }
} ?>

</html>