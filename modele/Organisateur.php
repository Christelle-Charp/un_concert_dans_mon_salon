<?php
/*
Classe décrivant l'objet Organisateur du modèle conceptuel
*/

class Organisateur extends _model{
    //Décrire l'objet réel: 
    protected $table = "organisateurs";
    protected $attributs = ["nom", "ville", "description_salle"];     // liste simple des noms des attributs sans l'id
    
    protected $liensMultiples = ["artistes"=>"Artiste"]; // tableau [ nomChamp => objetPointé, .... ]
    protected $relation = ["artistes"=>"id_artiste"];       // tableau [ nomDeLaTable => champLiantTable]

    //Ajout des infos du modéle physique:
    protected $idChamp = "id_organisateur"; 
    protected $id_organisateur = 0;
}