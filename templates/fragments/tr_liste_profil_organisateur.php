<?php

/*

Templates de fragment

Rôle : générer les tr du tableau des artistes
Paramètre : 
    $artiste : objet d'un artiste

*/
?>

<tr class="liste-artistes-orga">
    <td><?= htmlentities($artiste->get("nom")) ?></td>
    <td><?= htmlentities($artiste->get("type_musique")) ?></td>
    <td><?= htmlentities($artiste->get("zone_geo")) ?></td>
    <td><a href="afficher_detail_artiste.php?id_artiste=<?= htmlentities($artiste->id()) ?>" title="Voir les détails d'un artiste" >Détail</a></td>
    <td><a href="contacter.php?id_artiste=<?= htmlentities($artiste->id()) ?>" title="Contacter cet artiste" >Contacter</a></td>
</tr>