<?php
/*
Controleur:
    Mettre à jour le profil
    Extraire la liste des artistes
    Afficher la page de profil organisateur
Parametre: 
    $POST tous les champs du formulaire

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

// Récupération des paramètres : 
if(isset($_POST["email"])) {
    $email = $_POST["email"];
} else {
    $email = "";
}

// Traitement :
//Vérifier si l'organisateur existe
if(isset($_POST["id_organisateur"]) && !empty($_POST["id_organisateur"])){
    //si oui, je le me modifie
    //1-Je charge l'objet organisateur
    $organisateur = new Organisateur($_POST["id_organisateur"]);
    //2- Je change la valeur des attributs
    $organisateur->loadFromTab($_POST);
    //3-Je mets l'objet à jour dans la bdd
    $ok = $organisateur->update();
    $id_organisateur = $_POST["id_organisateur"]; 
} else{
    //si non, je le crée
    //1 - Je crée l'objet organisateur:
    $organisateur = new Organisateur();
    //2- Je le remplis avec les champs du post
    $organisateur->loadFromTab($_POST);
    //3 - Je le crée dans la bdd et je récupère l'id_organisateur
    $id_organisateur = $organisateur->insert();
    $ok = !empty($id_organisateur);
}

//4-J'ajoute l'id_organisateur et l'email dans l'objet de l'utilisateur connecté et je mets à jour la bdd
$utilisateur = new Utilisateur($_SESSION["id_utilisateur"]);
$utilisateur->set("organisateur", $id_organisateur);
$utilisateur->set("email", $email);
$maj = $utilisateur->update();

// Afficher la page: 
if($ok && $maj && !empty($id_organisateur) ) {
    $artiste = new Artiste();
    $list = $artiste->listAll();
    include "templates/pages/profil_organisateur.php";
}else {
    echo "<p>Enregistrement impossible, merci de vérifier votre saisie</p>";
    //on repasse l'organisateur pour pré remplir le formulaire
    $artiste = isset($organisateur) ? $organisateur : null;
    include "templates/pages/formulaire_modification_organisateur.php";

}