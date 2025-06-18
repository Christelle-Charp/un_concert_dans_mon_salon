<?php
/*
Controleur:
    Préparer affichage formulaire création compte
Parametre: 
    Neant

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

// Récupération des paramètrs : 



// Traitement :



// Afficher la page: 
include "templates/pages/formulaire_creation_compte.php";