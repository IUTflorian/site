<?php

require_once 'settings/bdd.inc.php';
require_once 'settings/init.inc.php';
$nbArticleParPage = 2; // Déclarer la variable du nombre d'articles par page
$pageCourante = isset($_GET['p']) ? $_GET['p'] : 1; // Variable qui contient la page courante

function returnIndex($nbArticleParPage, $pageCourante) { //Créer une fonction pour calculer l'index de départ de la page
    $debut = ($pageCourante - 1) * $nbArticleParPage;
// calcul des éléments
    return $debut;
}

$indexDepart = returnIndex($nbArticleParPage, $pageCourante);
//$sql = "SELECT COUNT(*) as nbarticle FROM articles WHERE publie = :publie";
$sql = $bdd->prepare("SELECT COUNT(*) as nbarticle FROM articles WHERE publie = :publie"); //preparation de la requête
$sql->bindValue(':publie', 1, PDO::PARAM_INT); //sécurisation de la requête
$sql->execute(); // execution de la requete 
$tabResult = $sql->fetchAll(PDO::FETCH_ASSOC);
$nbarticle = $tabResult[0]['nbarticle'];
$nbPage = ceil($nbarticle / $nbArticleParPage); //Calcul du nombre de page
//echo $indexDepart;
//echo '<br/><h2><b>Page : ' . $pageCourante . ' - Index de départ : <u>' . $indexDepart . '</u></b></h2>Nombre d\'article en bdd : ' . $nbarticle . ' - Nombre de pages à crééer : ' . $nbPage . '</h2>';
?>