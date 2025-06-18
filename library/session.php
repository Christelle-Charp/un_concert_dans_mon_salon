<?php

/*
Librairie des fonctions utiles pour la session

Recap des fonctions et leur retour:
- connecter un utilisateur: connecter($id) (Retourne true si l'utilisateur est déclaré connecté)
- Déconnecter un utilisateur connecté: deconnecter() (Retourne true si l'utilisateur a été déconnecté)
- Vérifier l'état de connexion d'un utilisateur: etatConnexion() (Retourne true si la connexion de l'utilisateur est active)
- Vérifier les codes de connexion: verifPseudo($pseudo, $mdp) (Retourne un objet utilisateur contenant les infos de l'utilisateur connecté)
- Récupérer l'utilisateur connecté: utilisateurConnecte()

Gestion des infos de connexion:
    - pas d'utilisateur connecté:
        $_SESSION["id"] sera soit vide soit égal à 0
    - un utilisateur connecté:
        $_SESSION["id"] contiendra l'id de l'utilisateur connecté
*/

function connecter($id) {
    //Role: déclarer qu'un utilisateur est connecté - Fonction d'activation de la connexion
    //Parametre:
    //      $id: id de l'utilisateur que l'on va déclarer connecté
    //Retour: true si réussi, sinon false

    $_SESSION["id_utilisateur"]=$id;
    $_SESSION["active"]=true;
    $_SESSION["derniere_action"]=time();
    $_SESSION["recherche"]="";
    return true;
}

function deconnecter() {
    //Role: déconnecter l'utilisateur connecté
    //Paramettre: Néant
    //Retour: true si réussi, sinon false

    $_SESSION["id_utilisateur"]=0;
    $_SESSION["active"]=false;
    $_SESSION["derniere_action"]=null;
    $_SESSION["recherche"]="";
    return true;
}

function etatConnexion() {
    //Role: indiquer si un utilisateur est connecté - Fonction de vérifiation de la connexion, si elle est active
    //      Prendre en compte le délai depuis la derniere action et si sup à 1h, déconnecter
    //Parametre: Neant
    //Retour: true si une connexion est encours, sinon false 

    //1ere étape, je vérifie si l'utilisateur est connecté
    if(!empty($_SESSION["id_utilisateur"]) && $_SESSION["active"]){
        //2eme étape, je vérifie si le délai entre maintenant et la derniere_action est inférieur à 1h
        if((time()-$_SESSION["derniere_action"]) < 3600) {
            //3eme étape, je mets à jour $_SESSION["derniere_action"]
            $_SESSION["derniere_action"] = time();
            //4eme étape, je retourne true
            return true;
        } else {
            //Si le délai est supérieur à 1h, je déconnecte l'utilisateur:
            deconnecter();
            return false;
        }
    }
    return false;
}

function verifPseudo($pseudo, $mdp) {
    //Role: vérifier que les codes de connexion sont valides et si oui, connecter utilisateur
    //Parametres:
    //      $pseudo: pseudo de connexion 
    //      $mdp: mot de passe à vérifier
    //Retour: un objet utilisateur correspondant aux codes s'il existe, sinon false

    //Chercher l'utilisateur correspondant au $pseudo:
    $utilisateur = new Utilisateur();
    $utilisateur->loadByPseudo($pseudo);
    //Si le pseudo n'existe pas: on retourne false:
    if (! $utilisateur->is()) {
        return false;
    }

    //Vérifier que le mot de passe correspond au pseudo:
        //si oui, on retourne l'objet utilisateur chargé
        //Sinon, on retourne false

    if(password_verify($mdp, $utilisateur->get("mdp"))) {
        connecter($utilisateur->id());
        return $utilisateur;
    } else {
        return false;
    }
}

function utilisateurConnecte() {
    //Role: Récupérer l'objet utilisateur connecté
    //Parametre: néant
    // Retour: objet utilisateur chargé avec les infos de l'utilisateur connecté ou un objet vide

    if(etatConnexion()) {
        return new Utilisateur($_SESSION["id_utilisateur"]);
    } else {
        return new Utilisateur;
    }
}