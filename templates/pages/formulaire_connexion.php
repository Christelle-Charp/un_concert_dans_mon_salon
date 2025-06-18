<?php
/*
Template de page complète : Mettre en forme le formulaire de saisie des champs de connection
Paramètres : 
    Neant
*/
?>
<!DOCTYPE html>
<html lang="fr">
<?php // Inclure le "head"
    $titre = "Connexion";       // Valoriser la variable $titre atendue par le fragment
    include "templates/fragments/head.php";
?>
<body>
    <div class="contenair">
        <h1><?= $titre ?></h1>
        <form action="connecter.php" method="POST">
            <label>Pseudo: 
                <input type="text" name="pseudo" value="">
            </label>
            <label>Mot de passe: 
                <input type="password" name="mdp" value="">
            </label>
            <input type="submit" value="Se connecter">
        </form>
        <button><a href="afficher_form_creation.php">Créer un compte</a></button>
    </div>
</body>
</html>