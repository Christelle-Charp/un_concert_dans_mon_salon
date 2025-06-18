<?php

// Initialisations communes à tous les controleurs 
// (à inclure en début de chaque controleur)


// mettre en place les messages d'erreur - Pensez à supprimer à la mise en production
ini_set('display_errors',1);
error_reporting(E_ALL);

// Initialiser / récupérer les infos de session
session_start();    // Permet de gerer les cookies, 
//récupère le tableau $_SESSION avec sa dernière valeur connue, surtout l'id de l'utilisateur connecté

// Charger les librairies
include "library/bdd.php";
include "library/session.php";

// Charger les différentes classes de modèle de données
include "modele/_model.php";
include "modele/Utilisateur.php";
include "modele/Conversation.php";
include "modele/Message.php";
include "modele/Organisateur.php";
include "modele/Artiste.php";

// Ouvrir la BDD dans la variable globale $bdd
global $bdd;
$bdd = new PDO("mysql:hote;dbname=nom-bdd;charset=UTF8", "nom-bdd", "password");
$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING) ;  // Pensez à supprimer à la mise en production