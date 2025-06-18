<?php
/*
Controleur:
    Modifier le statut de la conversation
    Préparer affichage la page de profil en fonction du type_utilisateur
Parametre: 
    $_GET["id_conversation"]
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
if(isset($_GET["id_conversation"])){
    $id_conversation = $_GET["id_conversation"];
} else {
    $id_conversation = 0;
}

// Traitement :
//1- Je modifie le statut de la conversation:
$conversation = new Conversation($id_conversation);
$statut = $conversation->get("statut");
if($statut == 1){
    $conversation->set("statut", 2);
    $conversation->update();
} else {
    $conversation->set("statut", 1);
    $conversation->update();
};

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