<?php
/*
Template de page complète : Mettre en forme le formulaire de saisie des champs de modification du profil organisateur
Paramètres : 
    $organisateur (optionnel): si le formulaire est utilisé pour la modification
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
            <form action="enregistrer_profil_organisateur.php" method="POST">
                <?php if (isset($organisateur) && $organisateur->is()) : ?>
                    <input type="hidden" name="id_organisateur" value="<?= htmlentities($organisateur->id()) ?>">
                <?php endif; ?>
                <label>Email:
                    <input type="text" name="email" value="<?= isset($email) ? htmlentities($email) : ''?>">
                </label>
                <label >Souhaitez-vous recevoir par email vos messages?
                    <input type="checkbox" name="notification" value="oui">
                </label>
                <label>Nom:
                    <input type="text" name="nom" value="<?= isset($organisateur) ? htmlentities($organisateur->get('nom')) : ''?>">
                </label>
                <label>Ville:
                    <input type="text" name="ville" value="<?= isset($organisateur) ? htmlentities($organisateur->get('ville')) : ''?>">
                </label>
                <label>Description Salle:
                    <input type="text" name="description_salle" value="<?= isset($organisateur) ? htmlentities($organisateur->get('description_salle')) : ''?>">
                </label>
                <input type="submit" value="Enregistrer">
            </form>
        </div>
    </div>
    
</body>
</html>