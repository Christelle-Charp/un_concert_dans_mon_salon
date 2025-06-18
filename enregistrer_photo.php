<?php
/*
Controleur:
    Enregistrer la photo
    verifier le type_utilisateur:
        si artiste:
            afficher page profil artiste
        si organisateur:
            extraire liste des artistes
            afficher page profil organisateur

Parametre: 
    $FILES photo

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
if (! isset($_FILES['photo'])) {
    echo "Merci de selectionner votre photo";
    exit;
}

$fichier = $_FILES['photo'];
$error = $fichier["error"];

// Traitement :
//Je vérifie la taille du fichier et si le chargement du fichier est ok
if ($error == UPLOAD_ERR_INI_SIZE or $error == UPLOAD_ERR_FORM_SIZE) {
    echo "La photo fournie est trop lourde";
    exit;
} else if ($error != UPLOAD_ERR_OK) {
    echo "Le chargement a échoué, veuillez ré-essayer ultérieurement";
    exit;
}

//Je crée le chemin du fichier et je l'enregistre dans l'utilisataur connecté
$utilisateur = utilisateurConnecte();
$stockage = $utilisateur->stockerPhoto($fichier["tmp_name"], $fichier["name"]);

if($stockage) {
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
} else {
    echo "<p>Impossible d'enregistrer votre photo.</p>";
    include "templates/pages/form_photo.php";
}


