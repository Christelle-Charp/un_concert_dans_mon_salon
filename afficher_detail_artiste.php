<?php
/*
Controleur:
    Extraire l'artiste
    Afficher la page detail artiste
Parametre: 
    $GET_id artiste

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
if(isset($_GET["id_artiste"])){
    $id_artiste = $_GET["id_artiste"];
} else {
    $id_artiste = 0;
}


// Traitement :
$artiste = new Artiste($id_artiste);

// Afficher la page: 
include "templates/pages/affichage_detail_artiste.php";