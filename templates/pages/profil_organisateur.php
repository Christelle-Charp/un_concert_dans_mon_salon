<?php
/*
Template de page complète : Mettre en forme l'affichage de la page d'accueil du profil organisateur
Paramètres : 
    $list: liste d'artistes
*/
$utilisateur = utilisateurConnecte();
?>
<!DOCTYPE html>
<html lang="fr">
<?php // Inclure le "head"
    $titre = "Profil organisateur";       // Valoriser la variable $titre atendue par le fragment
    include "templates/fragments/head.php";
?>
<body>
    <?php // Inclure le "header_organisateur"
    include "templates/fragments/header_organisateur.php";
    ?>
    <div class="contenair" data-profil-organisateur-utilisateur-id="<?=$utilisateur->id() ?>">
        <div class="row">
            <div class="gauche-organisateur">
                <h2>Tous les concerts:</h2>
                <form id="recherche-concerts" action="rechercher_concerts.php" method="POST">
                    <label >Votre recherche:
                        <input type="text" name="recherche" value="">
                    </label>
                    <input type="submit" value="Chercher">
                </form>
                <h3>Les concerts:</h3>
                <table>
                    <thead>
                        <tr>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th colspan="2"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            //Pour chaque ligne du tableau $list, je crée un tr
                            foreach ($list as $artiste) {
                                include "templates/fragments/tr_liste_profil_organisateur.php";
                            }
                        ?>
                    </tbody>
                </table>
            </div>
            <div class="droite-organisateur" id="liste_conversations_organisateur"></div>
        </div>
    </div>
    <script src="js/fonctions.js"></script>
</body>
</html>