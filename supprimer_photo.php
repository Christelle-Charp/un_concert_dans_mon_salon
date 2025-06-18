<?php
/*
Controleur:
    Supprimer la photo
    verifier le type_utilisateur:
        si artiste:
            afficher page profil artiste
        si organisateur:
            extraire liste des artistes
            afficher page profil organisateur

Parametre: 
    neant

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

// Traitement :
$utilisateur = utilisateurConnecte();
//Je supprime la photo:
$supression = $utilisateur->supprimerPhoto();

echo "<p>Aucune photo à supprimer</p>";
if($supression) {
    // Afficher la page: 
    //2- On vérifie son role pour l'envoyer sur la bonne page:
    $utilisateur = utilisateurConnecte();
    $role = $utilisateur->get("type_utilisateur");
    if($role == 1){
        //Si l'utilisateur est un artiste:
        include "templates/pages/profil_artiste.php";
    } else {
        $artiste = new Artiste();
        $list = $artiste->listAll();
        include "templates/pages/profil_organisateur.php";
    }
} else {
    echo "<p>Aucune photo à supprimer</p>";
    // Afficher la page: 
    $utilisateur = utilisateurConnecte();
    //2- On vérifie son role pour l'envoyer sur la bonne page:
    $role = $utilisateur->get("type_utilisateur");
    if($role == 1){
        //Si l'utilisateur est un artiste:
        include "templates/pages/profil_artiste.php";
    } else {
        $artiste = new Artiste();
        $list = $artiste->listAll();
        include "templates/pages/profil_organisateur.php";
    }
}


