<?php

// Classe mere qui va servir de base aux autres classes
// Cette classe se contruit de manière généraliste et abstraite pour pouvoir etre aplliquée aux classes "enfants"

//Classe _model: manipulation d'un objet générique du MCD

/*Recap des methodes et leur retour:
- constructeur pour charger un objet au moment de sa création: __construct($id = null)
- Vérifier si l'objet est chargé: is() (Retourne true si chargé sinon false)
- Récupérer l'id de l'objet: id() (la valeur de l'id sinon 0)
- Récupérer la valeur d'un champ donné: get($nom)
- Donne ou modifie la valeur d'un champ: set($nom, $valeur) (retourne true si ok sinon false)
Méthodes d'accés à la base de données:
- Charger un objet depuis une ligne de la bdd ayant un id donné: loadFromId($id) (retourne true si ok sinon false)
- Enregistrer l'objet courant dans la bdd: insert() (retourne l'id de l'objet crée par la bdd)
- Récupérer un tableau qui contient les ids d'un lien multiple: loadLiensMultiples() (retourne un tableau contenant tous les id)
- Recopier (mettre à jour) l'objet courant dans la bdd: update() (retourne true si ok sinon false) 
- Supprimer l'objet courant de la base de donnees (et remettre à 0 l'attribut id): delete() (retourne true si ok sinon false)
- Extraire de la bdd la liste de tous les objets sans tri: listAll() (Retourne tableau d'objet de la classe courante, indexé par l'id de l'objet)
- Récupérer tous les objets de la bdd qui correspondent (=) aux filtres et classeés dans l'ordre: function listFiltre($filtres=[], $order="") (retourne tableau d'objets)
- Récupérer tous les objets de la bdd qui contiennent (LIKE) les mots des filtres et classés dans l'ordre: listRecherche($filtres=[], $order="") (retourne tableau d'objets)
Methodes utilitaires:
- Construire la liste des champs d'un objet pour un SELECT: listeChampsPourSelect() (Retourne le texte à inclure dans $sql)
- Charger les valeurs des attributs à partir des données d'un un tableau indexé par les noms de champ sauf l'id: loadFromTab($tab) (retourne true si ok sinon false)
- Transformer, à partir d'un tableau simple de tableaux indexés des valeurs des champs (type de tableau récupéré de la bdd)
        // en un tableau de la classe courante: tab2TabObjects($tab) (tableau d'objets de la classe courante, indexé par l'id)

*/

class _model{
    //Décrire l'objet réel: attributs pour décrire l'objet mais sans les nommer->ce fait dans la classe qui utilisera la cette classe comme "mère"
    protected $table = "";
    protected $attributs = [];     // liste simple des noms des attributs sans l'id
    protected $liens = [];      // tableau  [ nomChamp => objetPointé, .... ]
    protected $liensMultiples = []; // tableau [ nomChamp => objetPointé, .... ]
    protected $relation = [];       // tableau [ nomDeLaTable => champLiantTable]

    //Ajout des infos du modéle physique:
    protected $valeurs =[];     //stockage des valeurs des attributs (quand remplis) [ "champ1" = "valeur1", "champ2" => "valeur2" ]
    protected $idChamp = "id";  //Par défaut si pas de personnalisation dans la classe "fille"
    protected $id = 0;          //Modele appartenant au physique, l'id de l'objet

    function __construct($id = null) {
        //Role: constructeur (appelé automatiquement au "new"): charge une ligne de la bdd si on précise un id
        //Parametre:
        //      $id: facultatif car dans la parenthèse il y a $id=null. id de la ligne de la bdd à charger dans l'objet qu'on crée
        if(! is_null($id)) {
            $this->loadFromId($id);
        }
    }

    function is() {
        // Rôle : vérifier si l'objet est chargé
        //parametre: néant
        //Retour: true s'il est chargé sinon false

        return !empty($this->id);

    }

    function id() {
        // Rôle : récupérer l'id de l'objet
        //Paramètre: néant
        //Retour: valeur de l'id ou sinon 0

        if(isset($this->id)){
            return $this->id;
        } else {
            return 0;
        }
    }

    //getters
    function get($nom) {
        //Role: getter, récupérer la valeur d'un champ donné
        //Parametre:
        //      $nom: nom du champ à récupérer
        //Retour: si attributs: valeur du champ ou valeur par défaut (chaine vide ""). si lien simple, retourne un objet. si lien multiple, retourne un tableau d'objet

        
        
        //si c'est un lien multiple (retourne un tableau d'objet)
        if (isset($this->liensMultiples[$nom])){
            //on veut retourné l'objet pointé
            $typeObjet = $this->liensMultiples[$nom];
            //J'initialise mon tableau d'objet:
            $tabObjets=[];

            //Vérifier si je manipule bien un tableau
            if (isset($this->valeurs[$nom]) && is_array($this->valeurs[$nom])) {
                //Pour chaque idChamp, je crée un nouvel objet de la classe $nom
                foreach($this->valeurs[$nom] as $id) {
                    //j'indexe la tableau par l'id
                    $tabObjets[$id] = new $typeObjet($id);
                }
            }
            return $tabObjets;

        }

        //si c'est un lien simple (ne retourne qu'un objet), 
        if (isset($this->liens[$nom])) {
            //on veut retourné l'objet pointé
            $typeObjet = $this->liens[$nom];
            if (!isset($this->valeurs[$nom]) || empty($this->valeurs[$nom])) {
            return null;
            }
            $objetPointe = new $typeObjet($this->valeurs[$nom]);
            return $objetPointe;
        }

        //Je vérifie si $nom fait partie des attributs dans le tableau listant le nom des attributs: $this->attributs
        if (!in_array($nom, $this->attributs)) {
            //si ce n'est pas un champ:
            return "";
        }
      
        //est ce qu'il a une valeur: il y a une valeur dans le tableau $this->valeurs (le tableau se charge au moment du loadFromId)
        if (isset($this->valeurs[$nom])) {
            //on retourne la valeur
            return $this->valeurs[$nom];
        } else {
            return "";
        }
    }

    //Setters

    function set($nom, $valeur) {
        //Role: setter, donne ou modifie la valeur d'un champ
        //Parametres:
        //      $nom: nom de l'attribut concerné
        //      $valeur: nouvelle valeur
        //retour: true si ok sinon false

        //Je vérifie si $nom fait partie des attributs dans le tableau listant le nom des attributs: $this->attributs
        if (!in_array($nom, $this->attributs)) {
            //si ce n'est pas un champ:
            return "";
        }

        //Je vérifie si $nom fait partie des liensMultiples, si oui, je dois stocker sous forme de tableau
        if (isset($this->liensMultiples[$nom])) {
            if (! is_array($valeur)) {
                $valeur = [$valeur]; // Je convertie ma valeur unique en tableau si $nom est un lienMultiple
            }
        }

        //On met la valeur dans le tableau $valeurs
        $this->valeurs[$nom] = $valeur;
        return true;

    }

    // Méthodes d'accés à la base de donnés
  
    //  loadFromId
    function loadFromId($id) {
        //Role: charger un objet depuis une ligne de la bdd ayant un idChamp donné
        //Parametre:
        //      $id: id à chercher dans la bdd pour charger l'objet
        //Retour: true si on a réussi sinon false

        //Cette methode va donc remplir le tableau $this->valeurs

        //Construire la requete pour extraire les infos de la bdd correspondant à l'idChamp
        $sql = "SELECT " .$this->listeChampsPourSelect() . " FROM `$this->table` WHERE `$this->idChamp`=:id";

        //Je prépare et j'exécute la requete pour récupérer un tableau
        $tab = bddGetRecord($sql, [":id" => $id]);
        if($tab == false) {
            //on n'a pas récupéré de ligne
            $this->valeurs=[];
            $this->id = 0;
            return false;
        }

        //on a objet:
        $this->loadFromTab($tab);
        $this->id = $id;
        // Synchronise l'id spécifique
        if (property_exists($this, $this->idChamp)) {
            $this->{$this->idChamp} = $id;
        }

        //Charger les liens multiples:
        $this->loadLiensMultiples();

        return true;
    }

    function loadLiensMultiples(){
        //Role: Récupérer un tableau qui contient les ids d'un lien multiple
        //Parametre:
        //      neant
        //Retour: un tableau contenant tous les id

        //J'initialise mon tableau vide:
        $ids = [];

       //Parcourir le tableau des liens multiples
        foreach ($this->liensMultiples as $nomChamp => $typeObjet) {
            // Je vérifie s'il y a une liaison entre table
            if (!isset($this->relation[$nomChamp])) {
                //Si pas de relation, j'ignore ce champ et je passe au suivant
                continue;
            }
            //Je récupére le champ qui lie les tables
            $liaison = $this->relation[$nomChamp];

            //Je récupère l'identifiant des objets liés
            $idChampLie = (new $typeObjet())->idChamp;

            //Je construis la requete sql:
            $sql = "SELECT `$idChampLie` FROM `$nomChamp` WHERE `$liaison` = :id";

            //Je prépare et j'execute ma requete

            $lignes = bddGetRecords($sql, [":id"=>$this->id] );

            //J'initialise mon tableau vide pour ce champ:
            $idsChamp = [];
            foreach ($lignes as $ligne) {
                if (isset($ligne[$idChampLie])) {
                    $idsChamp[] = $ligne[$idChampLie];
                }
            }

            $this->valeurs[$nomChamp] = $idsChamp;

            // On fusionne tous les ids pour le retour global 
            $ids = array_merge($ids, $idsChamp);
        }
        return $ids;
    }  


    // Insert
    function insert() {
        //Role: enregistrer l'objet courant dans la bdd 
        //Parametre: neant
        //Retour : l'id ou 0

        // Si l'objet est chargé : on refuse
        if($this->is()) {
            return false;
        }

        // Ne prendre que les attributs simples pour l'insertion
        $valeursSimples = [];
        foreach ($this->attributs as $nomChamp) {
            if (isset($this->valeurs[$nomChamp])) {
                $valeursSimples[$nomChamp] = $this->valeurs[$nomChamp];
            }
        }

        //j'utilise la fonction bddInsert($table, $valeursSimples)
        $id = bddInsert($this->table, $valeursSimples);

        if(empty($id)) {
            return 0;
        } else {
            //mise à jour de l'id dans l'objet
            $this->{$this->idChamp} = $id;
            $this->id = $id;
            return $id;
        }
    }

    //  update
    function update() {
        //Role: recopier (mettre à jour) l'objet courant dans la bdd
        //Parametre: 
        //  Neant
        // Retour: true si mise à jour ok sinon false

        //si l'objet n'est pas chargé: on refuse
        if(! $this->is()) {
            return false;
        }

        // Ne prendre que les attributs simples pour l'insertion
        $valeursSimples = [];
        foreach ($this->attributs as $nomChamp) {
            if (array_key_exists($nomChamp, $this->valeurs)) {
                $valeursSimples[$nomChamp] = $this->valeurs[$nomChamp];
            }
        }

        //j'utilise la fonction bddUpdate($table, $valeurs, $id):
        return bddUpdate($this->table, $valeursSimples, $this->idChamp, $this->{$this->idChamp});
    }

    //  delete
    function delete() {
        //Role: supprimer l'objet courant de la base de donnees (et remettre à 0 l'attribut id)
        //parametre: neant
        //Retour: true si suppression ok sinon false

        //Remarque: on efface l'id, pas les autres champs

        //si l'objet n'est pas chargé: on refuse
        if(! $this->is()) {
            return false;
        }

        //j'utilise la fonction bddDelete($table, $id):
        $execution = bddDelete($this->table, $this->idChamp, $this->{$this->idChamp});
        //Si cela a echoué: on retourne false
        //Sinon on vide l'id et on retourne true
        if(!$execution) {
            return false;
        } else {
            $this->{$this->idChamp} = null;
            return true;
        } 
    }

    //  listAll
    function listAll() {
        //Role: extraire de la bdd la liste de tous les objets
        //parametre: neant
        //Retour: tableau d'objet de la classe courante, indexé par l'id de l'objet

        //contruire la requete $sql pour extraire les infos de toutes les lignes de la bdd
        $sql = "SELECT " . $this->listeChampsPourSelect() . " FROM `$this->table`";

        //extraction des lignes sous forme de tableau:
        $tab = bddGetRecords($sql);

        //Transformation du tableau obtenu en tableau d'objets
        return $this->tab2TabObjects($tab);
    }

    //Fonction pour filtrer
    function listFiltre($filtres=[], $order="") {
        //Role: récupérer tous les objets de la bdd qui correspondent aux filtres et classeés dans l'ordre
        //parametre: 
        //      $filtres: tableau qui contient les criteres de filtre sous forme ["sexe" = "Femme"] 
        //      $order: le critere de classement 
        //Retour: tableau contenant tous les objets de la bdd qui correspondent aux filtres et classeés dans l'ordre

        // Initialisation des filtres et des paramètres
        $whereClauses = [];
        $param = [];

        //Construction du WHERE
        if (!empty($filtres)) {
            foreach ($filtres as $nomChamp=>$valeurChamp) {
                //ajouter à $whereClauses `nomChamp`" = ":nomChamp"
                $whereClauses[] = "`$nomChamp` = :$nomChamp";
                //Ajouter au tableau des paramètre $param[":nomChamp"] = la valeur
                $param[":$nomChamp"] = $valeurChamp;
            }

            //je met en forme la tableau $whereClauses en séparant chaque $whereClause par un and avec methode implode()
            $whereClause = implode(" AND ", $whereClauses);
        }
        

        //construire la requete sql
        $sql = "SELECT " . $this->listeChampsPourSelect() . " FROM `$this->table`";
        if (!empty($whereClauses)) {
            $sql .= " WHERE $whereClause" ;
        }
        if (!empty($order) && in_array($order, $this->attributs)) {
            $sql .= " ORDER BY `$order`";
        }

        // Extraction des lignes :
        $tab = bddGetRecords($sql, $param);

        // Transformation du tableau de tableau en un tableau d'objets
        return $this->tab2TabObjects($tab);

    }

    //Fonction pour la recherche
    function listRecherche($filtres=[], $order="") {
        //Role: Récupérer tous les objets de la bdd qui contiennent (LIKE) les mots des filtres et classés dans l'ordre
        //parametre: 
        //      $filtres: tableau qui contient les criteres de filtre sous forme ["nom" = "rechercheNom", "prenom" = "recherchePrenom"] 
        //      $order: le critere de classement 
        //Retour: tableau contenant tous les objets de la bdd qui correspondent aux filtres et classeés dans l'ordre

        // Initialisation des filtres et des paramètres
        $whereClauses = [];
        $param = [];

        //Construction du WHERE
        if (!empty($filtres)) {
            foreach ($filtres as $nomChamp=>$valeurChamp) {
                //ajouter à $whereClauses `nomChamp`" LIKE ":nomChamp"
                $whereClauses[] = "`$nomChamp` LIKE :$nomChamp";
                //Ajouter au tableau des paramètre $param[":nomChamp"] = la valeur
                $param[":$nomChamp"] = "%" . $valeurChamp . "%";
            }

            //je met en forme la tableau $whereClauses en séparant chaque $whereClause par un and avec methode implode()
            $whereClause = implode(" AND ", $whereClauses);
        }

        //construire la requete sql
        $sql = "SELECT " . $this->listeChampsPourSelect() . " FROM `$this->table`";
        if (!empty($whereClauses)) {
            $sql .= " WHERE $whereClause" ;
        }
        if (!empty($order) && in_array($order, $this->attributs)) {
            $sql .= " ORDER BY `$order`";
        }

        // Extraction des lignes :
        $tab = bddGetRecords($sql, $param);

        // Transformation du tableau de tableau en un tableau d'objets
        return $this->tab2TabObjects($tab);
    }

    //Methodes utilitaires

    function listeChampsPourSelect() {
        //Role: Construire la liste des champs de cet objet pour un SELECT, cad `id`, `nomChamp1`, ...
        //Paremtre: néant
        //Retour: le texte à inclure dans $sql

        $texte = "`{$this->idChamp}`";
        //On ajoute pour chaque attribut ,`nomChamp`
        foreach ($this->attributs as $nomChamp) {
            $texte .=" ,`$nomChamp`";
        }
        return $texte;
    }

    function loadFromTab($tab) {
        //Role: charger les valeurs des attributs à partir des données dans un tableau indexé par les noms de champ sauf l'id
        //Parametres:
        //      $tab: tableau indexé, dont les index sont des attributs (du modele physique)
        //Retour: true si ok sinon false

        //Pour chaque attribut de l'objet,
        // si il est dans le tableau, on charge sa valeur
        foreach($this->attributs as $nomChamp) {
            if (isset($tab[$nomChamp])) {       //Si on a une valeur dans la tableau $tab:
                //On affecte la valeur au champ: on se sert du setter  $this->set(nom du champ, valeur du champ)
                $this->set($nomChamp, $tab["$nomChamp"]);
            }
        }
        return true;
    }

    function tab2TabObjects($tab) {
        //Role: Transformer, à partir d'un tableau simple de tableaux indexés des valeurs des champs (type de tableau récupéré de la bdd)
        // en un tableau de la classe courante
        // Parametre:
        //      $tab: tableau à transformer
        //Retour: tableau d'objets de la classe courante, indexé par l'id

        //On part d'un tableau de résultat vide:
        $result = [];
        //Pour chaque ligne de $tab
        foreach ($tab as $ligne) {
            //on crée un objet de la classe courante
            $objet = new static();
            //On le charge
            $objet->loadFromTab($ligne);
            //loadFromTab() ne gere pas l'id donc on le rajoute apres
            if(isset($ligne[$this->idChamp])){
                $objet->{$this->idChamp} = $ligne[$this->idChamp]; 
                $objet->id = $ligne[$this->idChamp];     
            }
            //On l'ajoute à $result au bon index:
            $result[$objet->{$this->idChamp}] = $objet;
        }
        return $result;
    }
}