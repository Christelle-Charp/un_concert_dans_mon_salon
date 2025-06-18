<?php

/*

Templates de fragment

Rôle : générer le "header" de la page pour le profil organisateur
Paramètre : 
    neant

*/
$utilisateur = utilisateurConnecte();
$photo = $utilisateur->get("photo");
$path = "files/user/photo/";
$affichagePhoto = !empty($photo) ? $path . $photo : "asset/images/absence_photo.jpg";
?>
<header>
    <div class="contenair">
        <div class="row">
            <nav>
                <ul>
                    <li><a href="modifier_profil_organisateur.php" title="modifier son profil">Modifier son profil</a></li>
                    <li><a href="afficher_form_photo.php" title="modifier sa photo de profil">Modifier sa photo</a></li>
                    <li><a href="deconnecter.php" title="se déconnecter">Se déconnecter</a></li>
                    <li>
                        <div id="compteurMessage"></div>
                    </li>
                    <li><img class="photo-profil" src="<?= htmlspecialchars($affichagePhoto) ?>" alt="photo de profil"></li>
                </ul>
            </nav>
        </div>
    </div>
</header>