<?php
session_start();
require_once 'settings/bdd.inc.php';
require_once 'settings/init.inc.php';
include_once 'includes/header.inc.php';
require_once 'ArticlesParPages.php';
if (isset($_SESSION['statut_connexion']) == TRUE) {
    ?>
    <div class="alert alert-success " role="alert">
        <strong>Félecitation</strong> La connexion a réussi.
    </div>
    <?php
}
$sth = $bdd->prepare("SELECT id, titre, texte, DATE_FORMAT(date, '%d/%m/%Y') as date_fr FROM articles WHERE publie = :publie LIMIT $indexDepart, $nbArticleParPage"); //preparation de la requête

$sth->bindValue(':publie', 1, PDO::PARAM_INT); //sécurisation de la requête

$sth->execute(); // execution de la requete 
// $dernier_id = $sth->lastInsertId();
$tab_articles = $sth->fetchAll(PDO::FETCH_ASSOC);
//print_r($tab_articiles); // afficher les champs et la clé 
?>
<div class="span8">
    <?php
    foreach ($tab_articles as $value) {
        ?> 
        <h2><?php echo $value['titre'] ?>  </h2> 
        <img src="img/<?php echo $value['id'] ?>.jpg" width="100px" alt="titre"/>
        <p style="text-align: justify;"><?php echo $value['texte'] ?></p>
        <p><em><u> Publié le : <?php echo $value['date_fr'] ?> </u> </em> </p>
        <a href="article.php?id=<?= $value['id'] ?>"> Modifier l'article </a>
    <?php } ?>
    <div class="pagination">
        <ul>
            <li> <a>Page : </a> </li>
            <?php
            for ($i = 1; $i <= $nbPage; $i++) { // Créer les différents liens pour chaque pages
                ?>

                <li><a href="index.php?p=<?= $i; ?>"><?= $i; ?> </a></li>
                <?php
            }
            ?>
        </ul>
    </div>
    <!-- notifications -->

    <!-- contenu -->

</div> 

<?php
include_once 'includes/menu.inc.php';
include_once 'includes/footer.inc.php';
?>