<?php
session_start();
require_once 'settings/bdd.inc.php';
require_once 'settings/init.inc.php';

if (isset($_POST['ajouter'])) {
// print_r($_FILES); // Générer l'image dans le code php
//exit(); //arret du script

    $date_ajout = date("Y-m-d"); //Créer une variable contenant la date du jour
    echo $date_ajout;
    $_POST['date'] = $date_ajout; //Créer une variable dans le tableau contenant $date_ajout
//Condition ternaire 
    $_POST['publie'] = isset($_POST['publie']) ? 1 : 0;
// print_r($_POST); //récupèrer les infos de type post

    if (($_FILES['image']['error']) == 0) { //vérifie si l'image s'est bien affiché ou non
        $sth = $bdd->prepare("INSERT INTO articles(titre, texte, publie, date) VALUES (:titre, :texte, :publie, :date)");
        $sth->bindValue(':titre', $_POST['titre'], PDO::PARAM_STR); //sécurisation de la requête
        $sth->bindValue(':texte', $_POST['texte'], PDO::PARAM_STR); //sécurisation de la requête
        $sth->bindValue(':publie', $_POST['publie'], PDO::PARAM_INT); //sécurisation de la requête
        $sth->bindValue(':date', $_POST['date'], PDO::PARAM_STR); //sécurisation de la requête
        $sth->execute(); // execution de la requete 
        $id = $bdd->lastInsertId();

        //echo '<br/> <b><u>' . $dernier_id . '</u></b>';
        move_uploaded_file($_FILES['image']['tmp_name'], dirname(__FILE__) . "/img/$id.jpg"); // permet de déplacer le fichier uploader au bon endroit
        $_SESSION['ajout_article'] = TRUE;


        header("Location: article.php");
    } else {
        echo"Il y a un problème avec l'image";
    }
} elseif (isset($_POST['modifier'])) {
    $_POST['publie'] = isset($_POST['publie']) ? 1 : 0;
    $sql = $bdd->prepare("UPDATE articles SET titre = :titre, texte = :texte, publie = :publie WHERE id= :id"); // Création de la requête sql permettant de modifier l'article
    $sql->bindValue(':titre', $_POST['titre'], PDO::PARAM_STR); //sécurisation de la requête
    $sql->bindValue(':texte', $_POST['texte'], PDO::PARAM_STR); //sécurisation de la requête
    $sql->bindValue(':publie', $_POST['publie'], PDO::PARAM_INT); //sécurisation de la requête
    $sql->bindValue(':id', $_POST['id'], PDO::PARAM_STR); //sécurisation de la requête
    $sql->execute();
    $_SESSION['modification_article'] = TRUE;
    header("Location: article.php");
} else {
    include_once 'includes/header.inc.php';
    if (isset($_GET['id'])) {
        $sth = $bdd->prepare("SELECT * FROM articles WHERE id = :id"); //preparation de la requête
        $sth->bindValue(':id', $_GET['id'], PDO::PARAM_INT); //sécurisation de la requête
        $sth->execute(); // execution de la requete
        $tab_articles = $sth->fetchAll(PDO::FETCH_ASSOC);

//print_r($tab_articles);
        $tab_titre = $tab_articles[0]['titre'];
        $tab_texte = $tab_articles[0]['texte'];
        $tab_publie = $tab_articles[0]['publie'];
    }

    $tab_publie = isset($tab_publie) ? $tab_publie : 0;

    $type = isset($_GET['id']) ? $_GET['id'] : 0;

    if ($type == 0) {
        $bouton = 'ajouter';
    } else {
        $bouton = 'modifier';
    }
    ?>

    <div class="span8">
        <!-- notifications -->
        <?php
        if (isset($_SESSION['ajout_article'])) {
            ?>
            <div class="alert alert-success " role="alert">
                <strong>Félicitation!</strong> Votre article a été ajouté.
            </div>
            <?php
            unset($_SESSION['ajout_article']); // Destruction de la session
        } elseif (isset($_SESSION['modification_article'])) {
            ?>
            <div class="alert alert-success " role="alert">
                <strong>Félicitation!</strong> Votre article a été modifié.
            </div>
            <?php
            unset($_SESSION['modification_article']); // Destruction de la session  
        }
        ?>
        <!-- contenu -->
        <form action="article.php" method="post" enctype="multipart/form-data" id="form_article" name="form_article" >
            <input type="hidden" name="id" value="<?php
            echo $_GET['id'];
            ?>"/>
            <div class="clearfix">
                <label for="titre">Titre</label>
                <div class="input"><input type="text" name="titre" id="titre" value="<?php
                    if (isset($tab_titre)) {
                        echo $tab_titre;
                    }
                    ?>"></div>
            </div>
            <div class="clearfix">
                <label for="texte">Texte</label>
                <div class="input"><textarea name="texte" id="texte"><?php
                        if (isset($tab_titre)) {
                            echo $tab_texte;
                        }
                        ?></textarea></div>
            </div>
            <div class="clearfix">
                <label for="image">Image</label>
                <div class="input"><input type="file" name="image" id="image"></div>
            </div>
            <div class="clearfix">
                <label for="publie">Publié
                    <div class="input"><input type="checkbox" name="publie" id="publie"<?php
                        if ($tab_publie == 1) {
                            echo "checked";
                        }
                        ?>></div>
                </label>
            </div>
            <div class="form-actions">
                <input type="submit" name="<?= $bouton; ?>" value="<?= $bouton; ?>" class="btn btn-large btn-primary">
            </div>
        </form>
    </div>
    <?php
    include_once 'includes/menu.inc.php';
    include_once 'includes/footer.inc.php';
}
?>