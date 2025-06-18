<?php
/*
Controleur:
    Vérifier les codes de connexion
        si code ko:
            deconnecter utilisateur
            afficher page formulaire de connexion
        si code ok:
            connecteur l'utilisateur
            verifier le type_utilisateur:
                si artiste:
                    afficher page profil artiste
                si organisateur:
                    extraire liste des artistes
                    afficher page profil organisateur

Parametre: 
    $POST pseudo& mdp

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
//Néant

// Récupération des paramètrs : 
if(isset($_POST["pseudo"])) {
    $pseudo = $_POST["pseudo"];
}else {
    $pseudo = "";
}

if(isset($_POST["mdp"])) {
    $mdp = $_POST["mdp"];
} else {
    $mdp = "";
}

// Traitement :
//Vérifier si les codes de connexion correspondent à un utilisateur
$utilisateur = verifPseudo($pseudo, $mdp);
//Si l'utilisateur n'existe pas:
if($utilisateur === false) {
    //On envoie sur la page pour se connecter:
    include "templates/pages/formulaire_connexion.php";
    //on termine le programme:
    exit;
} 
// Si l'utilisateur existe:
//1- On le connecte
connecter($utilisateur->id());

// Afficher la page: 
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



