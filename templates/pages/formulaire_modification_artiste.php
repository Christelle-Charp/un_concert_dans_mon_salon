<?php
/*
Template de page complète : Mettre en forme le formulaire de saisie des champs de modification du profil artiste
Paramètres : 
    $artiste (optionnel): si le formulaire est utilisé pour la modification
*/
$utilisateur = utilisateurConnecte();
$email = $utilisateur->get("email");
?>
<!DOCTYPE html>
<html lang="fr">
<?php // Inclure le "head"
    $titre = "Modifier son profil";       // Valoriser la variable $titre atendue par le fragment
    include "templates/fragments/head.php";
?>
<body>
    <div class="contenair">
        <div class="row">
            <h1><?= $titre ?></h1>
            <form action="enregistrer_profil_artiste.php" method="POST">
                <?php if (isset($artiste) && $artiste->is()) : ?>
                    <input type="hidden" name="id_artiste" value="<?= htmlentities($artiste->id()) ?>">
                <?php endif; ?>
                <label>Email:
                    <input type="text" name="email" value="<?= isset($email) ? htmlentities($email) : ''?>">
                </label>
                <label >Souhaitez-vous recevoir par email vos messages?
                    <input type="checkbox" name="notification" value="oui">
                </label>
                <label>Nom de scène:
                    <input type="text" name="nom" value="<?= isset($artiste) ? htmlentities($artiste->get('nom')) : ''?>">
                </label>
                <label>Présentation Artiste:
                    <input type="text" name="presentation_groupe" value="<?= isset($artiste) ? htmlentities($artiste->get('presentation_groupe')) : ''?>">
                </label>
                <label>Description Musique:
                    <input type="text" name="description_musique" value="<?= isset($artiste) ? htmlentities($artiste->get('description_musique')) : ''?>">
                </label>
                <label>Type Musique:
                    <input type="text" name="type_musique" value="<?= isset($artiste) ? htmlentities($artiste->get('type_musique')) : ''?>">
                </label>
                <label>Description Offre de concert:
                    <input type="text" name="description_offre" value="<?= isset($artiste) ? htmlentities($artiste->get('description_offre')) : ''?>">
                </label>
                <label>Zone géographique:
                    <input type="text" name="zone_geo" value="<?= isset($artiste) ? htmlentities($artiste->get('zone_geo')) : ''?>">
                </label>
                <label>Tarif:
                    <input type="text" name="tarif" value="<?= isset($artiste) ? htmlentities($artiste->get('tarif')) : ''?>">
                </label>
                <input type="submit" value="Enregistrer">
            </form>
        </div>
    </div>
    
</body>
</html>