<?php
/*
Template de page complète : Mettre en forme le formulaire d'enregistrement d'une photo
Paramètres : 
    Neant
*/
?>
<!DOCTYPE html>
<html lang="fr">
<?php // Inclure le "head"
    $titre = "Modifier sa photo";       // Valoriser la variable $titre atendue par le fragment
    include "templates/fragments/head.php";
?>
<body>
    <div class="contenair">
        <h1><?= $titre ?></h1>
        <form action="enregistrer_photo.php" method="POST" enctype="multipart/form-data">
            <label>Charger votre photo: 
                <input type="file" name="photo" accept="image/*" >
            </label>
            <input type="submit" value="Enregistrer">
            <input type="hidden" name="MAX_FILE_SIZE" value="2097152">
        </form>
        <button><a href="supprimer_photo.php" title="Supprimer sa photo de profil">Supprimer la photo</a></button>
    </div>
</body>
</html>