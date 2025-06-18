<?php
/*
Controleur:
    Vérifier si le pseudo existe:
        si oui:
            afficher page formulaire_creation_compte
        si non:
            créer le pseudo
            connecter l'utilisateur
            afficher formulaire_modification_artiste/organisateur
Parametre: 
    $POST pseudo & mdp & type_utilisateur

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

// Récupération des paramètrs : 
if(isset($_POST["pseudo"]) && isset($_POST["mdp"])) {
    $pseudo = $_POST["pseudo"];
    $mdp = $_POST["mdp"];
}
$type_utilisateur = $_POST["type_utilisateur"] ?? null;


// Traitement :
//1-vérifier si le pseudo existe
$utilisateur = new Utilisateur();
$utilisateur->loadByPseudo($pseudo);
//2-si le pseudo existe, on affiche la page de création de compte
if ($utilisateur->is()) {
    //Afficher la page de creation de compte
    echo "<p>Ce pseudo existe déjà</p>";
    include "templates/pages/formulaire_creation.php";
} else {
    if(!empty($type_utilisateur)) {
        $utilisateur->set("type_utilisateur", $type_utilisateur);
        //3-si le pseudo n'existe pas, on le crée
        $utilisateur->set("pseudo", $pseudo);
        //4-On hash le mot de passe
        $mdp_hash = password_hash($mdp, PASSWORD_DEFAULT);
        $utilisateur->set("mdp", $mdp_hash);
        $id = $utilisateur->insert();
        //5-Connecter l'utilisateur
        //$utilisateur = new Utilisateur($id);
        connecter($id);
        //6-Afficher la pages correspondant au type_utilisateur:
        if($type_utilisateur == 1){
            include "templates/pages/formulaire_modification_artiste.php";
        } else {
            include "templates/pages/formulaire_modification_organisateur.php";
        }
    } else {
        //Afficher la page de creation de compte
        echo "<p>Merci de choisir un type de profil</p>";
        include "templates/pages/formulaire_creation.php";
    }
}
