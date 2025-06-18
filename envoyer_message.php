<?php
/*
Controleur:
    Enregistrer le message dans la bdd
    Récupérer les messages de la conversation
    Préparer affichage affichage_conversation.php
Parametre: 
    $Get_id_conversation
    $POST contenu

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

if(isset($_POST["contenu"])){
    $contenu = $_POST["contenu"];
}else{
    $contenu = "";
}

// Traitement :
//Je récupere les infos dont j'ai besoin pour créer le message
$utilisateur=utilisateurConnecte();
$role = $utilisateur->get("type_utilisateur");
$conversation = new Conversation($id_conversation);
if($role == 1){
    $id_expediteur = $conversation->get("artiste")->id();
    $id_destinataire = $conversation->get("organisateur")->id();
}else{
    $id_expediteur = $conversation->get("organisateur")->id();
    $id_destinataire = $conversation->get("artiste")->id();
}
//Je crée un nouvel objet message
$message = new Message();
//Je le charge avec les infos
$message->set("contenu", $contenu);
$message->set("conversation", $id_conversation);
$message->set("expediteur", $id_expediteur);
$message->set("destinataire", $id_destinataire);
$message->set("statut", 1);
//Je l'enregistre dans la bdd
$message->insert();

//Je récupère toute la conversation
$conversation = new Conversation($id_conversation); 
// Afficher la page: 
include "templates/pages/affichage_conversation.php";