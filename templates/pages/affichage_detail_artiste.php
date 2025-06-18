<?php
/*
Template de page complète : Mettre en forme l'affichage des détails d'un profil artiste
Paramètres : 
    $artiste: objet artiste
*/
?>
<!DOCTYPE html>
<html lang="fr">
<?php // Inclure le "head"
    $titre = "Détail d'un artiste";       // Valoriser la variable $titre atendue par le fragment
    include "templates/fragments/head.php";
?>
<body>
    <div class="contenair">
        <div class="row">
            <h1><?= $titre ?></h1>
            <div class="descriptif-artiste">
                <p>Nom de scène: <?= htmlentities($artiste->get("nom")) ?></p>
                <p>Présentation Artiste: <?= htmlentities($artiste->get("presentation_groupe")) ?></p>
                <p>Description Musique: <?= htmlentities($artiste->get("description_musique")) ?></p>
                <p>Type Musique: <?= htmlentities($artiste->get("type_musique")) ?></p>
                <p>Description Offre de concert: <?= htmlentities($artiste->get("description_offre")) ?></p>
                <p>Zone géographique: <?= htmlentities($artiste->get("zone_geo")) ?></p>
                <p>Tarif: <?= htmlentities($artiste->get("tarif")) ?></p>
                <div class="cta">
                    <a href="contacter.php?id_artiste=<?= htmlentities($artiste->id()) ?>">Contacter</a>
                </div>
            </div>
            <a href="rechercher_concerts.php">Retour</a>
        </div>
    </div>
    
</body>
</html>