<?php
/*
Classe décrivant l'objet Message du modèle conceptuel
*/

class Message extends _model{
    //Décrire l'objet réel: 
    protected $table = "messages";
    protected $attributs = ["contenu", "creation", "expediteur", "destinataire", "conversation","statut"];     // liste simple des noms des attributs sans l'id
    protected $liens = ["conversation"=>"Conversation"];      // tableau  [ nomChamp => objetPointé, .... ]

    //Ajout des infos du modéle physique:
    
    protected $idChamp = "id_message";  
    protected $id_message = 0;

    //Fonctions surchargées

    function countMessagesNonLus($id_conversation, $utilisateur){
        //Role: compter le nombre de messages avec le statut non lu ou l'utilisateur est le destinataire dans une conversation
        //Parametre:
        //      $id_conversation
        //      $utilisateur
        //Retour: le nombre de messages non lus ou l'utilisateur est le destinataire de la conversation

        //Récupérer l'id artiste ou organisateur selon le role de l'utilisateur
        $role=$utilisateur->get("type_utilisateur");
        $id_cherche = ($role == 1) ? $utilisateur->get("artiste")->id() : $utilisateur->get("organisateur")->id();

        //construction de la requete
        $sql=
        "SELECT COUNT(*) AS `nb_messages_non_lus`  
        FROM `messages` 
        WHERE `conversation`=:id_conversation 
        AND `statut`=1 
        AND `destinataire` = :id_cherche";

        //Construction des parametres:
        $param=[":id_conversation"=>$id_conversation, ":id_cherche"=>$id_cherche];

        //Je récupère la ligne du tableau
        $ligne = bddGetRecord($sql, $param);

        //Je fais mon retour:
        return $ligne["nb_messages_non_lus"] ?? 0; 
    }

    function countAllMessagesNonLus($utilisateur){
        //Role: Compter tous les messages non lu dont l'utilisateur est le destinataire
        //Parametre:
        //      $utilisateur: un objet avec les infos chargées de l'utilisateur connecté
        //Retour: le nombre de messages non lu dont l'utilisateur est le destinataire

        //Récupérer l'id artiste ou organisateur selon le role de l'utilisateur
        $role=$utilisateur->get("type_utilisateur");
        $id_cherche = ($role == 1) ? $utilisateur->get("artiste")->id() : $utilisateur->get("organisateur")->id();

        //Construire la requete:
        $sql="SELECT COUNT(*) AS `nb_messages_non_lus`  
        FROM `messages` 
        WHERE `statut`=1 
        AND `destinataire` = :id_cherche";

        //Construction des parametres:
        $param=[":id_cherche"=>$id_cherche];

        //Je récupère la ligne du tableau
        $ligne = bddGetRecord($sql, $param);

        //Je fais mon retour:
        return $ligne["nb_messages_non_lus"] ?? 0;
    }

    function recupererMessagesForConversation($id_conversation){
        //Role: Récupérer tous les messages d'une conversation
        //Parametres: 
        //      $id_conversation
        //Retour: tableau contenant tous les messages de la conversation

        //Construire la requete
        $sql=
        "SELECT `id_message`, `contenu`, `creation`, `expediteur`, `destinataire`, `conversation`, `statut` 
        FROM `messages` 
        WHERE `conversation` = :id_conversation ";

        //Construction des parametres:
        $param = [":id_conversation"=>$id_conversation];

        //Je récupére les lignes du tableau
        $lignes = bddGetRecords($sql, $param);

        //Je le transforme en tableau d'objets
        $list = $this->tab2TabObjects($lignes);

        //Je fais le retour:
        return $list;
    }

}