<?php

/*

Templates de fragment

Rôle : générer les tr du tableau des artistes
Paramètre : 
    $artiste : objet d'un artiste

*/
?>

<tr class="liste-artiste">
    <td><?= htmlentities($artiste->get("nom")) ?></td>
    <td><?= htmlentities($artiste->get("type_musique")) ?></td>
    <td><?= htmlentities($artiste->get("zone_geo")) ?></td>
</tr>