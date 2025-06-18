<?php
/*
Classe décrivant l'objet Conversation du modèle conceptuel
*/

class Conversation extends _model{
    //Décrire l'objet réel: 
    protected $table = "conversations";
    protected $attributs = ["organisateur", "artiste", "statut"];     // liste simple des noms des attributs sans l'id
    protected $liens = ["organisateur"=>"Organisateur", "artiste"=>"Artiste"];      // tableau  [ nomChamp => objetPointé, .... ]
    protected $liensMultiples = ["messages"=>"Message"]; // tableau [ nomChamp => objetPointé, .... ]
    protected $relation = ["messages"=>"id_message"];       // tableau [ nomDeLaTable => champLiantTable]

    //Ajout des infos du modéle physique:
    
    protected $idChamp = "id_conversation";  
    protected $id_conversation = 0;

    //Methodes surchargées:
    function verifConversation($id_organisateur, $id_artiste){
        //Role: Vérifier si une conversation existe entre l'organisateur et l'artiste et retourner cette conversation
        //Parametres:
        //      $id_organisateur
        //      $id_artiste
        //Retour: l'id_conversation si elle existe sinon false

        //Construction de la requete SQL:
        $sql = "SELECT `id_conversation` FROM `conversations` WHERE `organisateur`=:id_organisateur AND `artiste`=:id_artiste";
        //Construction des parametres:
        $param = [":id_organisateur"=>$id_organisateur, ":id_artiste"=>$id_artiste];

        //Je récupére la seule ligne du tableau:
        $ligne = bddGetRecord($sql, $param);

        //Je vérifie si le tableau $ligne est vide ou pas
        if (empty($ligne)){
            return false;
        }
        $id_conversation = $ligne["id_conversation"];
        return $id_conversation; 
    }

    function getNbMessagesNonLus($utilisateur){
        //Role: compter le nombre de messages avec le statut non lu de la conversation en cours
        //Parametre:
        //      Neant
        //Retour: le nombre de messages non lus de la conversation
        $message = new Message();
        return $message->countMessagesNonLus($this->id_conversation, $utilisateur);
    }

    function recupererConversationsForUtilisateur($id_utilisateur, $role, $statut){
        //Role: extraire liste des conversations d'un utilisateur selon son type_utilisateur et le statut de la conversation
        //Parametres:
        //      $role qui correspond au type utilisateur
        //      $statut: le statut de la conversation
        //Retour: $list, tableau indexé contenant les conversations de l'utilisateur

        //construction de la requete en fonction du role:
        if($role == 1){
            //Si l'utilisateur est un artiste
            $sql=
                "SELECT `c`.*, MAX(`m`.`creation`) AS `date_dernier_message`
                FROM `utilisateurs` `u`
                LEFT JOIN `conversations` `c` ON `u`.`artiste` = `c`.`artiste`
                LEFT JOIN `messages` `m` ON `c`.`id_conversation` = `m`.`conversation`
                WHERE `u`.`id_utilisateur` = :id_utilisateur
                AND `u`.`type_utilisateur` = 1
                AND `c`.`statut` = :statut
                GROUP BY `c`.`id_conversation`
                ORDER BY `date_dernier_message` DESC";
        } elseif ($role == 2) {
            //Si l'utilisateur est un organisateur
            $sql=
                "SELECT `c`.*, MAX(`m`.`creation`) AS `date_dernier_message`
                FROM `utilisateurs` `u`
                LEFT JOIN `conversations` `c` ON `u`.`organisateur` = `c`.`organisateur`
                LEFT JOIN `messages` `m` ON `c`.`id_conversation` = `m`.`conversation`
                WHERE `u`.`id_utilisateur` = :id_utilisateur
                AND `u`.`type_utilisateur` = 2
                AND `c`.`statut` = :statut
                GROUP BY `c`.`id_conversation`
                ORDER BY `date_dernier_message` DESC";
        }

        //Construction des parametres:
        $param = [":id_utilisateur"=>$id_utilisateur, ":statut"=>$statut];

        //Je récupère toutes les lignes du tableau
        $lignes=bddGetRecords($sql, $param);

        //Je transforme le tableau en tableau d'objets:
        $list = $this->tab2TabObjects($lignes);

        //je fais le retour
        return $list;
    }

    function getMessages(){
        //Role: Récupérer tous les messages de la conversation en cours
        //Parametres: 
        //      Neant
        //Retour: tableau contenant tous les messages de la conversation
        $message = new Message();
        $list = $message->recupererMessagesForConversation($this->id_conversation);
        return $list;
    }

}