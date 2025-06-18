<?php
/*
Template de page complète : Mettre en forme le formulaire de saisie des champs de création d'un compte
Paramètres : 
    Neant
*/
?>
<!DOCTYPE html>
<html lang="fr">
<?php // Inclure le "head"
    $titre = "Creation compte";       // Valoriser la variable $titre atendue par le fragment
    include "templates/fragments/head.php";
?>
<body>
    <div class="contenair">
        <h1><?= $titre ?></h1>
        <form action="creer_pseudo.php" method="POST">
            <label>Pseudo: 
                <input type="text" name="pseudo" value="">
            </label>
            <label>Mot de passe: 
                <input type="password" name="mdp" value="">
            </label>
            <label>Type de profil
                <input type="radio" id="option1" name="type_utilisateur" value="1">
                <label for="option1">Artiste</label>
                <input type="radio" id="option2" name="type_utilisateur" value="2">
                <label for="option2">Organisateur</label>
            </label>
            <input type="submit" value="Enregistrer">
        </form>
    </div>
</body>
</html>