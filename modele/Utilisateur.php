<?php
/*
Classe décrivant l'objet Utilisateur du modèle conceptuel
*/

class Utilisateur extends _model{
    // Décrire l'objet réel : attributs pour décrire l'objet réel
    // On décrit le modèle conceptuel

    protected $table = "utilisateurs";
    protected $attributs = ["pseudo", "mdp", "email", "type_utilisateur", "artiste", "organisateur", "photo"];
    protected $liens = ["organisateur"=>"Organisateur", "artiste"=>"Artiste"];      // tableau  [ nomChamp => objetPointé, .... ]

    protected $liensMultiples = ["conversations"=>"Conversation", "messages"=>"Message"];
    protected $relation = ["messages"=>"id_message", "conversations"=>"id_conversation"];       // tableau [ nomDeLaTable => champLiantTable]

    protected $idChamp = "id_utilisateur";
    protected $id_utilisateur = 0;


    //Fonctions surchargées pour la classe Utilisateur:

    function loadByPseudo($pseudo) {
        // Rôle : charger un utilisateur en connaissant son pseudo qui correspond à l'adresse mail et qui est unique
        // Paramètres :
        //      $pseudo : pseudo cherché
        // Retour : true, false sinon

        // Construction de la requête SQL :
        $sql = "SELECT " . $this->listeChampsPourSelect() . " FROM `$this->table` WHERE `pseudo`= :pseudo";
        // Construction des parametres:
        $param = [ ":pseudo" => $pseudo];

        // Je recupere la seule ligne du tableau
        $ligne = bddGetRecord($sql, $param);

        //Je vérifie si le tableau $ligne est vide ou pas
        if (empty($ligne)) {
            return false;
        }

        $this->loadFromTab($ligne);
        $this->id = $ligne["id_utilisateur"];
        return true;
    }

    function recupererConversations($role, $statut){
        //Role: extraire liste des conversations d'un utilisateur selon son type_utilisateur et le statut de la conversation
        //Parametres:
        //      $role qui correspond au type utilisateur
        //      $statut: le statut de la conversation
        //Retour: $list, tableau indexé contenant les conversations de l'utilisateur

        $conversation = new Conversation();
        return $conversation->recupererConversationsForUtilisateur($this->id_utilisateur, $role, $statut);
    }

    function stockerPhoto($tempFile, $initialName){
        //Role: Stocker un fichier sur le serveur et enregistrer le chemin du fichier dans la bdd
        //Parametres:
        //      $tempFile: fichier temporaire
        //      $initialName: nom initial du fichier
        //Retour: True si ajout du chemin ok sinon false
        
        // On commence par vérifier si le ficher existe
        if (!file_exists($tempFile)) return false;
        // On vérifie que l'objet utilisateur est bien chargé
        if(!$this->is()) return false;
        //Construction du nom du fichier: id_utilisateur + extension du fichier d'origine
        $name = $this->id() . "." . pathinfo($initialName, PATHINFO_EXTENSION);
        // Je génère un nombre aléatoire entre 10 et 99
        $dir = rand(10,99);
        //Je crée l'adresse de la zone de stockage des photos des utilisateurs sur le serveur
        $path = "files/user/photo";
        //Je vérifie s'il existe déja une photo pour l'utilisateur 
        $oldFile = "$path/" . $this->get("photo");
        //et si oui, je la supprime
        if (file_exists($oldFile) && is_file($oldFile)) unlink($oldFile);
        //sinon, je stock le nouveau fichier
        //Je crée le dossier généré aléatoirement si besoin
        //Je vérifie si le dossier n'existe pas
        if (!is_dir("$path/$dir")) {
            //Je vérifie que le dossier est bien crée sinon je renvoie false
            if (!mkdir("$path/$dir", 0770, true)) return false; // is_dir() vérifie si le dossier existe et mkdir() crée tous les dossiers et sous dossiers nécessaires
        } 
        //Je vérifie que le fichier vient bien d'un upload du formulaire
        if(!is_uploaded_file($tempFile)) return false;
        //Je déplace le fichier temporaire dans le dossier 
        $stockage = move_uploaded_file($tempFile, "$path/$dir/$name");
        if (!$stockage) return false;

        //Une fois le fichier stocké, on met à jour la bdd avec le chemin relatif (pas le chemin absolu en cas de modification de serveur)
        //Je mets à jour l'objet
        $this->set("photo", "$dir/$name");
        //Je mets à jour la bdd
        $maj = $this->update();

        //Je fais le retour
        return $maj;
    }

    function supprimerPhoto() {
        //Role: Supprimer la photo du profil utilisateur
        //Parametre:
        //      Neant
        //Retour: True si suppression ok sinon false

        //Je donne le chemin
        $path = "files/user/photo";
        $photo = "$path/" . $this->get("photo");
        //Je vérifie s'il y a une photo dans l'utilisateur connecté
        if(file_exists($photo) && is_file($photo)){
            //je supprime le lien
            unlink($photo);
            //Je mets à jour l'objet en effaçant le lien
            $this->set("photo", null);
            //Je mets à jour l'objet dans la bdd
            $this->update();
            return true;
        } else {
            return false;
        }
    }
}