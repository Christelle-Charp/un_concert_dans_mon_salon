<?php
/*
Controleur:
    Extraire liste des artistes en fonction des critères de recherche
Parametre: 
    $POST recherche

*/

/* Initialisations comprenant:
    - gestion des messages erreur - qui sont à supprimer au passage en production
    - demarrage de la session
    - chargement des librairies:
        - celle des fonctions de la bdd
        - celle des fonctions de session
    - chargement des classes
        - _model
        - Utilisateur
        - Conversation
        - Message
        - Organisateur
        - Artiste
    - gestion des acces à la bdd et création de la global $bdd
    Tout est regroupé dans le init:     
*/
include "library/init.php";

// Vérification des droits:
//  Si on n'est pas connecté:
if (! etatConnexion()) {
    //  Envoi l'utilisateur sur la page de connexion pour rentrer ces acces:
    //  Termine le programme (fin du controlleur)
    include "templates/pages/formulaire_connexion.php";
    exit;
}

// Récupération des paramètrs : 
//Pensez à enregistrer le mot de recherche dans $_SESSION["recherche"]
if(isset($_POST["recherche"])){
    $recherche = $_POST["recherche"];
    $_SESSION["recherche"]=$recherche;
} else {
    $recherche = $_SESSION["recherche"] ?? "";
}

// Traitement :
//Je crée un objet artiste
$artiste = new Artiste ();
$list = $artiste->rechercheArtiste($recherche);

// Afficher la page: 
include "templates/pages/profil_organisateur.php";