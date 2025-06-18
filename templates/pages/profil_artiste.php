<?php
/*
Template de page complète : Mettre en forme l'affichage de la page d'accueil du profil artiste
Paramètres : 
    Neant
*/
$utilisateur = utilisateurConnecte();
?>
<!DOCTYPE html>
<html lang="fr">
<?php // Inclure le "head"
    $titre = "Profil artiste";       // Valoriser la variable $titre atendue par le fragment
    include "templates/fragments/head.php";
?>
<body>
    <?php // Inclure le "header_artiste"
    include "templates/fragments/header_artiste.php";
    ?>
    <div class="contenair" data-profil-artiste-utilisateur-id="<?=$utilisateur->id() ?>">
        <div class="row">
            <div class="gauche-artiste">
                <h2>Mes salles disponibles:</h2>
                <div class="salles" id="liste_organisateurs"></div>
            </div>
            <div class="droite-artiste" id="liste_conversations_artiste"></div>
        </div>
    </div>
    <script src="js/fonctions.js"></script>
</body>
</html>