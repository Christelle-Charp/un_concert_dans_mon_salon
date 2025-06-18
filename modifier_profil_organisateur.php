<?php
/*
Controleur:
    Préparer affichage formulaire_modification_organisateur
Parametre: 
    Neant
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
    include "templates/pages/formulaire_connection.php";
    exit;
}

// Récupération des paramètrs : 
//Néant

// Traitement :
$utilisateur = utilisateurConnecte();
//$id_organisateur = $utilisateur->get("organisateur");
//$organisateur = new Organisateur($id_organisateur);
$organisateur = $utilisateur->get("organisateur");

//Affichage de la page:
include "templates/pages/formulaire_modification_organisateur.php";