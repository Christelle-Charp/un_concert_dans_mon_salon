<?php
/*
Controleur pour Ajax:
    Récupérer le message de la conversation
    Changer son statut en lu si message non lu
Parametre: 
    $GET_id message

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
if (isset($_GET["id_message"])) {
    $id_message = $_GET["id_message"];
} else {
    $id_message = 0;
}
// Traitement :
$message = new Message($id_message);
//Je change le statut du message si c'est un message non lu
if($message->get("statut") == 1){
    $message->set("statut", 2);
    $message->update();
}

if ($id_message == 0 && isset($_GET["id_conversation"])) {
    // On veut juste le formulaire pour une nouvelle conversation
    $id_conversation = $_GET["id_conversation"];
} else {
    $id_conversation = "";
}


// Afficher la page: 
include "templates/fragments/formulaire_message.php";