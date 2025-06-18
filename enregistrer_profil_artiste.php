<?php
/*
Controleur:
    Mettre à jour le profil
    Afficher la page de profil artiste
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
//Vérifier si l'artiste existe:
    if(isset($_POST["id_artiste"]) && !empty($_POST["id_artiste"])) {
        // Si oui, je le modifie:
        //1- Je charge l'objet $artiste
        $artiste = new Artiste($_POST["id_artiste"]);
        //2- Je change la valeur des attributs
        $artiste->loadFromTab($_POST);
        //3 - Je mets à jour l'objet
        $ok = $artiste->update();
        $id_artiste = $_POST["id_artiste"];
    } else {
        // Si non, je le crée
        //1-Je crée un objet artiste
        $artiste = new Artiste();
        //2-Je remplis l'artiste avec les champs du post
        $artiste->loadFromTab($_POST);
        //3-Je crée l'artiste dans la base de donnée et je récupére l'id_artiste
        $id_artiste = $artiste->insert();
        $ok = !empty($id_artiste);
    }

//4-J'ajoute l'id_artiste et l'email dans l'objet de l'utilisateur connecté et je mets à jour la bdd
$utilisateur = new Utilisateur($_SESSION["id_utilisateur"]);
$utilisateur->set("artiste", $id_artiste);
$utilisateur->set("email", $email);
$maj = $utilisateur->update();

// Afficher la page: 
if($ok && $maj && !empty($id_artiste) ) {
    include "templates/pages/profil_artiste.php";
}else {
    echo "<p>Enregistrement impossible, merci de vérifier votre saisie</p>";
    //on repasse l'artiste pour pré remplir le formulaire
    $artiste = isset($artiste) ? $artiste : null;
    include "templates/pages/formulaire_modification_artiste.php";

}
