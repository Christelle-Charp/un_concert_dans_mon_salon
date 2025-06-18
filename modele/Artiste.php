<?php
/*
Classe décrivant l'objet Artiste du modèle conceptuel
*/

class Artiste extends _model{
    //Décrire l'objet réel: 
    protected $table = "artistes";
    protected $attributs = ["nom", "presentation_groupe", "description_musique", "type_musique", "description_offre", "zone_geo", "tarif"];     // liste simple des noms des attributs sans l'id
    
    protected $liensMultiples = ["organisateurs"=>"Organisateur"]; // tableau [ nomChamp => objetPointé, .... ]
    protected $relation = ["organisateurs"=>"id_organisateur"];       // tableau [ nomDeLaTable => champLiantTable]

    //Ajout des infos du modéle physique:
    protected $idChamp = "id_artiste";  
    protected $id_artiste = 0;

    //Fonctions surchargées

    function rechercheArtiste($recherche){
        //Role: Recuperer tous les artistes qui correspondent à la recherche
        //Parametre:
        //      $recherche: mot de la recherche
        //Retour: $list avec tous les artiste

        //Construire la requete 
        $sql="
        SELECT `id_artiste`, `nom`, `presentation_groupe`, `description_musique`, `type_musique`, `description_offre`, `zone_geo`, `tarif` 
        FROM `artistes` 
        WHERE `nom` LIKE :recherche 
        OR `presentation_groupe` LIKE :recherche 
        OR `description_musique` LIKE :recherche 
        OR `type_musique` LIKE :recherche 
        OR `description_offre` LIKE :recherche 
        OR `zone_geo` LIKE :recherche 
        OR `tarif` LIKE :recherche
        GROUP BY `id_artiste`";

        //Construction des parametres:
        $param = [":recherche"=> "%" . $recherche . "%"];

        //Je récupère toutes les lignes du tableau
        $lignes=bddGetRecords($sql, $param);

        //Je transforme le tableau en tableau d'objets:
        $list = $this->tab2TabObjects($lignes);

        //je fais le retour
        return $list;
    }
}