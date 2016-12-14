<?php

try {
    $bdd = new PDO('mysql:host=localhost;dbname=u190997886_planc;charset=utf8', 'u190997886_planc', 'atkoD7LAlP');
    $bdd->exec('set names utf8');
    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Exception $e) {
    die('Erreur !: ' . $e->getMessage()) . "<br/>";
}
?>