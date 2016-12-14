<?php

session_start();
require_once 'settings/bdd.inc.php';
require_once 'settings/init.inc.php';
include_once 'includes/header.inc.php';
require_once 'libs/Smarty.class.php';

if (isset($_POST['connexion'])) {

    $email = $_POST['email'];
    $mdp = $_POST['mdp'];
//echo $email;
//echo $mdp;
    $sql = "SELECT * FROM utilisateurs WHERE email = :email AND mdp = :mdp";
    $sth = $bdd->prepare($sql = "SELECT * FROM utilisateurs WHERE email = :email AND mdp = :mdp");
    $sth->bindValue(':email', $_POST['email'], PDO::PARAM_STR); //sécurisation de la requête
    $sth->bindValue(':mdp', $_POST['mdp'], PDO::PARAM_STR); //sécurisation de la requête
    $sth->execute();
    $count = $sth->rowCount();
    if ($count == 1) {
        echo "la connexion est réussie";
        $sid = md5($email . time());
        echo $sid;
        setcookie('sid', $sid, time() + 30); // Création d'un cookie
        $sql = $bdd->prepare("UPDATE utilisateurs SET sid = :sid WHERE email = :email");
        $sql->bindValue(':sid', $sid, PDO::PARAM_STR);
        $sql->bindValue(':email', $_POST['email'], PDO::PARAM_STR);
        $sth->execute();
        $_SESSION['statut_connexion'] = TRUE;
        header("Location: index.php");
    } else {

        $_SESSION['statut_connexion'] = FALSE;
        header("Location: connexion.php");
    }
} else {
    $smarty = new Smarty(); //

    $smarty->setTemplateDir('templates/');
    $smarty->setCompileDir('templates_c/');
    if (isset($_SESSION['statut_connexion'])) {
        $smarty->assign('statut_connexion', $_SESSION['statut_connexion']);
    }
    unset($_SESSION['statut_connexion']);

//** un-comment the following line to show the debug console
//    $smarty->debugging = true;



    $smarty->display('connexion.tpl');
    
include_once 'includes/menu.inc.php';
include_once 'includes/footer.inc.php';  
}
?>