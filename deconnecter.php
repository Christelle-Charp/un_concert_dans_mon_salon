<?php
/*
Controleur:
    Déconnecter l'utilisateur
    Extraite liste des artistes
    Préparer affichage page d'accueil
Parametre: 
    neant

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
//Néant

// Récupération des paramètrs : 
//Neant


// Traitement :
//1- Je déconnecte:
deconnecter();
//2- Je prépare la liste des artistes pour la page d'accueil
$artiste = new Artiste();
$list = $artiste->listAll();

// Afficher la page: 
include "templates/pages/affichage_accueil.php";