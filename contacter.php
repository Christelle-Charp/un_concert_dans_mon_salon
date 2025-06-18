<?php
/*
Controleur:
    Vérifier si la conversation existe:
        si oui:
            afficher page conversation
        si non:
            créer la conversation
            afficher page conversation
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
}else {
    $id_artiste = 0;
}

// Traitement :
//1- Vérifie si la conversation entre l'organisateur et l'artiste existe:
    //Je récupere les id organisateur & artiste
$utilisateur = utilisateurConnecte();
$id_organisateur = $utilisateur->get("organisateur")->id();

    //Je crée une conversation:
$conversation = new Conversation();
    // Je vérifie si la conversation existe:
$id_conversation = $conversation->verifConversation($id_organisateur, $id_artiste);
if($id_conversation == false){
    //Si la conversation n'existe pas, je le crée:
    $conversation = new Conversation();
    //Je remplis l'objet:
    $conversation->set("organisateur", $id_organisateur);
    $conversation->set("artiste", $id_artiste);
    $conversation->set("statut", 1);
    //J'enregistre la conversation dans la bdd et je recupere l'id_conversation
    $id_conversation = $conversation->insert();
    //Je charge mon objet conversation:
    $conversation = new Conversation($id_conversation);
    //J'affiche la page
    include "templates/pages/affichage_conversation.php";
} else {
    // Si la conversation existe:
    $conversation = new Conversation($id_conversation);
    //J'affiche la page
    include "templates/pages/affichage_conversation.php";
}